<?php

namespace Judge\Leptir;

use Leptir\Task\AbstractLeptirTask;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Judge\Document\UserSubmission;
use Judge\Document\AlgorithmUserSubmission;
use Zend\ServiceManager\ServiceLocatorInterface;

class JudgeAlgorithm extends AbstractLeptirTask implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Main logic of the task. This method has to be implemented for every task.
     *
     * @return mixed
     */
    protected function doJob()
    {
        $submissionId = $this->getString('asId');

        /* @var DocumentManager $documentManager */
        $documentManager = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');

        /** @var \Judge\Repository\AlgorithmUserSubmission $submissionRepo */
        $submissionRepo = $documentManager->getRepository(
            'Judge\Document\AlgorithmUserSubmission'
        );
        /** @var \Judge\Repository\BaseProblem $problemRepo */
        $problemRepo = $documentManager->getRepository(
            'Judge\Document\ActiveProblem'
        );
        /** @var \Judge\Repository\UserSubmission $userSubmissionRepo */
        $userSubmissionRepo = $documentManager->getRepository(
            'Judge\Document\UserSubmission'
        );
        /** @var \Judge\Repository\User $userRepo */
        $userRepo = $documentManager->getRepository(
            'Judge\Document\User'
        );

        $submission = $submissionRepo->find(new \MongoId($submissionId));
        if (!($submission instanceof AlgorithmUserSubmission)) {
            $this->addResponseLine('Submission not found');
            return self::EXIT_ERROR;
        }

        $problemId = $submission->getProblemId();
        $userId = $submission->getUserId();

        /** @var \Judge\Document\ActiveProblem $problem */
        $problem = $problemRepo->find(new \MongoId($problemId));
        /** @var \Judge\Document\User $user */
        $user = $userRepo->find(new \MongoId($userId));

        if (!$problem) {
            $this->addResponseLine('Problem not found.');
            return self::EXIT_ERROR;
        }

        if (!$user) {
            $this->addResponseLine('User not found.');
            return self::EXIT_ERROR;
        }

        $dockerBuildCommand = sprintf(
            "docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/submissions/%s/%s:/solution %s-build",
            (string)$userId,
            (string)$problemId,
            $submission->getLanguage()
        );


        $dockerRunCommand = sprintf(
            "docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/%s/in:/in judgy-run",
            (string)$problemId
        );

        $dockerCompareCommand = sprintf(
            "docker run --rm --volumes-from judgy-ambassador -v /var/www/judge_data/%s/out:/correct_out judgy-compare",
            (string)$problemId
        );

        shell_exec($dockerBuildCommand);

        $stdout = shell_exec($dockerRunCommand);
        $this->logInfo("Build message: " . $stdout);
        $lines = explode(PHP_EOL, $stdout);

        $cntOk = 0;
        $cntTotal = 0;
        $response = '';
        if (strpos($stdout, 'FAIL') !== false) {
            $this->logInfo("Build failed!");
            $this->logInfo($stdout);
            // build failed
            $submission->setStatus($submission::STATUS_FAIL);
            $submission->setProcessedMessage(
                implode(
                    PHP_EOL,
                    array_slice($lines, 1)
                )
            );
            $submission->setStatus($submission::STATUS_BUILD_FAIL);
        } else {
            $stdout = shell_exec($dockerCompareCommand);
            $submission->setProcessedMessage($stdout);
            $lines = explode(PHP_EOL, $stdout);

            foreach ($lines as $line) {
                if (!$line) {
                    continue;
                }
                $this->logInfo($line);
                if (preg_match('/Test #([0-9]+) ([A-Z]+)/', $line, $match)) {
                    ++$cntTotal;
                    $status = trim($match[2]);

                    switch ($status) {
                        case "OK":
                            $response .= "<p>Test #" . (string)$match[1] . ": Passed.</p>";
                            break;
                        case "TLE":
                            $response .= "<p>Test #" . (string)$match[1] . ": Time limit exceeded.</p>";
                            break;
                        case "WE":
                            $response .= "<p>Test #" . (string)$match[1] . ": Wrong answer.</p>";
                            break;
                        case "RTE":
                            $response .= "<p>Test #" . (string)$match[1] . ": Run-time error.</p>";
                            break;
                    }
                    $cntOk += ($status == 'OK');
                }
            }
            $problemSolved = ($cntOk == $cntTotal);
            if ($problemSolved) {
                $submission->setStatus($submission::STATUS_SUCCESS);
            } else {
                $submission->setStatus($submission::STATUS_FAIL);
            }
        }

        $submission->setTotalCases($cntTotal);
        $submission->setSolvedCases($cntOk);
        if ($cntTotal > 0) {
            $submission->setScore($problem->getDifficulty() * (1.0 * $cntOk / $cntTotal));
        } else {
            $submission->setScore(0.0);
        }

        /** @var \Judge\Document\UserSubmission $userSubmission */
        $userSubmission = $userSubmissionRepo->findForUserAndProblem($problem, $user);
        if (!$userSubmission) {
            $userSubmission = UserSubmission::create(
                $problem, $user
            );
            $problem->setAttempts($problem->getAttempts() + 1);
        }

        if ($submission->getStatus() == $submission::STATUS_SUCCESS && !$userSubmission->getSolved()) {
            $problem->setSolved($problem->getSolved() + 1);
            $user->setAlgorithmSolved($user->getAlgorithmSolved() + 1);
            $userSubmission->setAttempts($userSubmission->getAttempts() + 1);
            $userSubmission->setSolved(true);
            $userSubmission->setDateSolved(new \DateTime());
        } elseif ($submission->getStatus() != $submission::STATUS_SUCCESS) {
            if (!$userSubmission->getSolved()) {
                $userSubmission->setAttempts($userSubmission->getAttempts() + 1);
            } else {
                // problem already solved, but this submission is wrong
                $userSubmission->setSolved(false);
            }
        }
        $this->logInfo('Saving data to database.');
        $documentManager->persist($submission);
        $documentManager->persist($problem);
        $documentManager->persist($user);
        $documentManager->persist($userSubmission);
        $documentManager->flush();

        return self::EXIT_SUCCESS;
    }
}