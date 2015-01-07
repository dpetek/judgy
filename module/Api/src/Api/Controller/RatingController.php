<?php
namespace Api\Controller;

use Zend\View\Model\JsonModel;
use Api\Exception\Core\NoPermissionException;
use Api\Exception\Core\MissingResource;
use Judge\Document\Rating;

class RatingController extends BaseApiController
{
    public function create($data)
    {
        if (!$this->getCurrentUser()) {
            throw new NoPermissionException();
        }

        $request = $this->getRequest();
        $post = $request->getPost();

        $targetId = $post['target'];
        $targetType = $post['targetType'];
        $value = intval($post['value']);

        /** @var \Judge\Repository\Rating $ratingRepo */
        $ratingRepo = $this->getDocumentManager()->getRepository(
            'Judge\Document\Rating'
        );
        $targetRepo = $this->getDocumentManager()->getRepository(
            $targetType
        );
        /** @var \Core\Document\BaseRatable $target */
        $target = $targetRepo->find(new \MongoId($targetId));

        if (!$target) {
            throw new MissingResource();
        }
        /** @var \Judge\Document\Rating $rating */
        $rating = $ratingRepo->findForUserAndTarget(
            $this->getCurrentUser(),
            $target
        );

        if (!$rating) {
            $rating = Rating::create($this->getCurrentUser(), $target, $value);
            $target->addVote($value);
        } else {
            $target->changeVote($rating->getValue(), $value);
            $rating->setValue($value);
        }
        $this->getDocumentManager()->persist($rating);
        $this->getDocumentManager()->persist($target);
        $this->getDocumentManager()->flush();

        return new JsonModel(
            array(
                'success' => true
            )
        );
    }
}
