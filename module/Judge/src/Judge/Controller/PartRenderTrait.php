<?php

namespace Judge\Controller;

use Zend\View\Model\ViewModel;
use Judge\Document\ActiveProblem;
use Judge\Document\User;
use Judge\Document\Rating;

trait PartRenderTrait
{
    public function renderProblemStatement(ActiveProblem $problem, User $user = null)
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
}
