<?php

namespace AppBundle\Controller\v1;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use AppBundle\Http\ResourceCreatedResponse;
use AppBundle\Services\LocationPatch as LocationPatchService;
use AppBundle\Model\Api\Json\LocationPatch\Base as LocationPatchModel;
use AppBundle\Model\Api\Json\LocationPatch\StopPointFromReporter as StopPointLocationPatchFromReporterModel;


/**
 * @Route("/patches/location")
 */
class LocationPatchController extends BaseController
{
    /**
     * @Security("has_role('ROLE_ROOT')")
     * @Route("/_all", name="v1_get_all_location_patches")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return new JsonResponse(
            $this->getDoctrine()
                ->getRepository('AppBundle:LocationPatch')
                ->findBy(['user' => $this->getUser()])
        );
    }

    /**
     * @Security("has_role('ROLE_V1_LOCATION_PATCH_CREATE')")
     * @Route("", name="v1_location_patch_create")
     * @Method("POST")
     */
    public function createAction(Request $request, LocationPatchService $locationPatchService)
    {
        $result = $this->deserializeOr400($request->getContent(), LocationPatchModel::class);
        $this->validOr400($result);
        $locationPatchService->create($this->getUser(), $result);

        return new ResourceCreatedResponse();
    }

    /**
     * @Security("has_role('ROLE_V1_LOCATION_PATCH_CREATE_FROM_REPORTER')")
     * @Route("/from_reporter", name="v1_location_patch_create_from_reporter")
     * @Method("POST")
     */
    public function createFromReporter(Request $request, LocationPatchService $locationPatchService)
    {
        $result = $this->deserializeOr400($request->getContent(), StopPointLocationPatchFromReporterModel::class);
        $this->validOr400($result);
        $locationPatchService->create($this->getUser(), $result, true);

        return new ResourceCreatedResponse();
    }
}
