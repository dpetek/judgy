<?php

namespace Api\Controller;

use Api\Exception\Core\NoPermissionException;
use Judge\Document\Tutorial;
use Zend\View\Model\JsonModel;
use Zend\Http\PhpEnvironment\Request;

class TutorialController extends BaseApiController
{
    public function postSubmitAction()
    {
        if (!$this->getCurrentUser() || !$this->getCurrentUser()->getIsAdmin()) {
            throw new NoPermissionException();
        }

        /** @var Request $request */
        $request = $this->getRequest();
        $post = $request->getPost();

        $title = $post->get('title');
        $content = $post->get('content');
        $type = $post->get('type', 'algorithm');

        $tutorial = Tutorial::create($this->getCurrentUser(), $title, $content, $type);
        $this->getDocumentManager()->persist($tutorial);
        $this->getDocumentManager()->flush();

        return new JsonModel(
            array(
                'error' => false,
                'success' => true
            )
        );
    }
}

