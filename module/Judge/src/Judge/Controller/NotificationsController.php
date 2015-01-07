<?php

namespace Judge\Controller;

use Zend\View\Model\ViewModel;

class NotificationsController extends BaseJudgeController
{
    public function indexAction()
    {
        $view = new ViewModel();

        return $view;
    }
}
