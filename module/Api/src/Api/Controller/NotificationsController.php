<?php
namespace Api\Controller;

use Zend\View\Model\JsonModel;
use Api\Exception\Core\NoPermissionException;
use Api\Exception\Core\MissingResource;

class NotificationsController extends BaseApiController
{
    public function postMarkViewedAction()
    {
        if (!$this->getCurrentUser()) {
            throw new NoPermissionException();
        }

        /** @var \Judge\Repository\Notification $notificationsRepo */
        $notificationsRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\Notification'
        );
        $notificationsRepo->markViewedForUser($this->getCurrentUser());
        return new JsonModel(
            array(
                'success' => true
            )
        );
    }
}
