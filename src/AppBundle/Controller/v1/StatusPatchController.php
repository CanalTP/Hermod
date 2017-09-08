<?php

namespace AppBundle\Controller\v1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Http\ResourceCreatedResponse;
use AppBundle\Services\StatusPatch as StatusPatchService;
use AppBundle\Model\Api\Json\StatusPatch\Elevator as ElevatorStatusModel;

/**
 * @Route("/patches/status")
 */
class StatusPatchController extends BaseController
{
    /**
     * @Security("has_role('ROLE_ROOT')")
     * @Route("/_all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return $this->json(
            $this->getDoctrine()
                ->getRepository('AppBundle:StatusPatch')
                ->findBy(['user' => $this->getUser()])
        );
    }

    /**
     * @Security("has_role('ROLE_V1_STATUS_PATCH_CREATE_EQUIPMENT_STATUS')")
     * @Route("", name="v1_status_patch_create_equipment_status")
     * @Method("POST")
     */
    public function createEquipmentStatusAction(Request $request, StatusPatchService $statusPatchService)
    {
        $result = $this->deserializeOr400($request->getContent(), ElevatorStatusModel::class);
        $this->validOr400($result);
        $statusPatchService->create($this->getUser(), $result);

        return new ResourceCreatedResponse();
    }
}
