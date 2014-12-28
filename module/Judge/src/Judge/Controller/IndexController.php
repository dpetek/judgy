<?php
namespace Judge\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends BaseJudgeController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function loginAction()
    {
        if ($this->getCurrentUser()) {
            return $this->redirect()->toRoute('home');
        }
        return new ViewModel();
    }

    public function registerAction()
    {
        if ($this->getCurrentUser()) {
            return $this->redirect()->toRoute('home');
        }
        return new ViewModel();
    }
}
