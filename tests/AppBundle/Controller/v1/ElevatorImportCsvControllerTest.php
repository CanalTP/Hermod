<?php

namespace Tests\AppBundle\Controller\v1;

class EquipmentControllerTest extends ApiTestCase
{
    private $missingHeadersCsv = <<<CSV
code;station_id;direction
CSV;

    private $elevatorsCsv = <<<CSV
id;code;station_id;station_name;human_location;direction;source_name;maj
435;"NAP PBS3 ILOT 13";8775802;"GARE DE NANTERRE PREFECTURE";"Oui";"Voirie - Quai";"saint germain en laye";"2013-05-27"
437;"NAP PBS6 (Nord)";8775802;"GARE DE NANTERRE PREFECTURE";"Oui";"Salle des Echanges - Quai 1/1C - Quai 2";"St-Germain-en-Laye et Boissy - Chessy";
436;"NAP PBS5 (Sud)";8775802;"GARE DE NANTERRE PREFECTURE";"Oui";"Salle des Echanges - Quai 2c";"Cergy-Poissy";
448;"E0036/08/1";8711371;"GARE DE VAL DE FONTENAY";"Oui";"rue du Bois Galon - passage souterrain";"CHESSY";
CSV;

    public function testIsSecured()
    {
        $this->assertSecured('POST', 'elevators/import/csv');
    }

    public function testMandatoryCsvHeadersAreEnforced()
    {
        $response = $this->client->request('POST', 'elevators/import/csv', ['body' => $this->missingHeadersCsv]);
        $this->assertError($response, 400, 'bad_request', 'missing headers: \'id\', \'station_name\', \'human_location\', \'source_name\'');
    }

    public function testCreateElevatorsWithCsv()
    {
        $response = $this->client->request('POST', 'elevators/import/csv', ['body' => $this->elevatorsCsv]);
        $this->assertEquals(200, $response->getStatusCode());
        $created = $this->getBody($response)['created'];
        $this->assertEquals(4, count($created));
        $this->assertDbCount(5, 'Elevator');

        // equipments already imported, this should not create any equipment
        $response = $this->client->request('POST', 'elevators/import/csv', ['body' => $this->elevatorsCsv]);
        $created = $this->getBody($response)['created'];
        $this->assertEquals(0, count($created));
        $this->assertDbCount(5, 'Elevator');
    }

    public function testUpdateElevatorsWithCsv()
    {
        $this->client->request('POST', 'elevators/import/csv', ['body' => $this->elevatorsCsv]);
        $this->assertDbCount(5, 'Elevator');

        $csv3ModifiedEquipments = str_replace('GARE DE NANTERRE', 'GARE DE PARIS', $this->elevatorsCsv);
        $response = $this->client->request('POST', 'elevators/import/csv', ['body' => $csv3ModifiedEquipments]);

        $updated = $this->getBody($response)['updated'];
        $this->assertStatus($response, 200);
        $this->assertEquals(3, count($updated));
        $this->assertDbCount(5, 'Elevator');

        // re-playing the same update request, it shouldn't do anything as the data are already up to date
        $response = $this->client->request('POST', 'elevators/import/csv', ['body' => $csv3ModifiedEquipments]);
        $updated = $this->getBody($response)['updated'];
        $this->assertStatus($response, 200);
        $this->assertEquals(0, count($updated));
        $this->assertDbCount(5, 'Elevator');
    }
}
