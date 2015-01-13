<?php

namespace Judge\Controller;

use Zend\View\Model\ViewModel;
use Judge\Document\ActiveProblem;
use Judge\Document\BaseProblem;
use Judge\Document\User;
use Judge\Document\Rating;

trait PartRenderTrait
{
    public function renderProblemStatement(BaseProblem $problem, User $user = null)
    {
        $viewModel = new ViewModel();
        $viewModel->setVariables(
            array(
                'problem' => $problem,
                'user' => $user
            )
        );
        $viewModel->setTemplate('judge/parts/problem_statement');
        return $viewModel;
    }

    public function renderRateProblem(ActiveProblem $problem, Rating $rating = null, User $user = null)
    {
        $viewModel = new ViewModel();

        $viewModel->setVariables(
            array(
                'problem' => $problem,
                'rating' => $rating,
                'user' => $user
            )
        );

        $viewModel->setTemplate('judge/parts/rate_problem');
        return $viewModel;
    }

    public function renderProblemMeta(ActiveProblem $problem)
    {
        $viewModel = new ViewModel();

        $viewModel->setVariables(
            array(
                'problem' => $problem
            )
        );
        $viewModel->setTemplate('judge/parts/problem_meta');
        return $viewModel;
    }

    public function renderMiscSubmissionsList($submissions)
    {
        $viewModel = new ViewModel();

        /** @var \Judge\Repository\BaseProblem $problemsRepo */
        $problemsRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\ActiveProblem'
        );
        $pIds = array();
        /** @var \Judge\Document\MiscUserSubmission $miscSubmission */
        foreach ($submissions as $miscSubmission) {
            $pIds[] = $miscSubmission->getProblemId();
        }
        $problems = $problemsRepo->findInIdsAssoc($pIds);

        $viewModel->setVariables(
            array(
                'submissions' => $submissions,
                'problemsLookup' => $problems
            )
        );

        $viewModel->setTemplate('judge/parts/misc_submissions_list');
        return $viewModel;
    }

    public function renderAlgorithmSubmissionsList($submissions)
    {
        $viewModel = new ViewModel();

        /** @var \Judge\Repository\BaseProblem $problemsRepo */
        $problemsRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\ActiveProblem'
        );
        $pIds = array();
        /** @var \Judge\Document\AlgorithmUserSubmission $miscSubmission */
        foreach ($submissions as $algorithmSubmission) {
            $pIds[] = $algorithmSubmission->getProblemId();
        }
        $problems = $problemsRepo->findInIdsAssoc($pIds);

        $viewModel->setVariables(
            array(
                'submissions' => $submissions,
                'problemsLookup' => $problems
            )
        );

        $viewModel->setTemplate('judge/parts/algorithm_submissions_list');
        return $viewModel;
    }
}
