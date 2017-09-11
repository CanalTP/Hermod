<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController extends BaseController
{
    /**
     * @Security("has_role('ROLE_ROOT')")
     * @Route("/status", name="v1_status")
     * @Method("GET")
     */
    public function statusAction()
    {
        return new JsonResponse([
            'status' => 'OK',
            'version' => $this->hasParameter('version') ? $this->getParameter('version') : 'unknown'
        ]);
    }
}
