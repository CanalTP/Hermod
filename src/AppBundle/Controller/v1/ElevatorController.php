<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Elevator;
use AppBundle\Http\Exception\BadRequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Util\Csv;

/**
 * @Route("/elevators")
 */
class ElevatorController extends BaseController
{
    /**
     * @Security("has_role('ROLE_DEV')")
     * @Route("/_all")
     * @Method("GET")
     */
    public function getAllAction()
    {
        return $this->json(
            $this->getDoctrine()->getRepository('AppBundle:Elevator')->findAll()
        );
    }

    /**
     * @Security("has_role('ROLE_V1_ELEVATOR_IMPORT_CSV')")
     * @Route("/import/csv", name="v1_elevator_import_csv")
     * @Method("POST")
     */
    public function importCsvAction(Request $request)
    {
        $mandatoryHeaders = ['code', 'station_id', 'station_name', 'human_location', 'source_name', 'direction'];
        try {
            $csv = Csv::parse($request->getContent(), $mandatoryHeaders);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestException($e->getMessage());
        }

        return $this->json($this->getDoctrine()->getRepository(Elevator::class)->importFromCsv($csv));
    }
}
