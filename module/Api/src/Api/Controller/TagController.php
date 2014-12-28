<?php
namespace Api\Controller;

use Judge\Document\User;
use Zend\Config\Writer\Json;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class TagController extends BaseApiController
{
    public function getLookupAction()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();
        $prefix = $query['query'];

        /** @var \Judge\Repository\Tag $repo */
        $repo = $this->getDocumentManager()->getRepository(
            'Judge\Document\Tag'
        );
        $tags = $repo->findByPrefix($prefix);

        $response = array();

        foreach ($tags as $tag) {
            $response[] = $tag->toArray();
        }

        return new JsonModel($response);
    }
}
