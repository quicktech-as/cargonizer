<?php

namespace Quicktech\CargonizerTest;

use PHPUnit\Framework\TestCase;
use Quicktech\Cargonizer\CargonizerManager;
use GuzzleHttp\Client;


class CargonizerManagerTest extends TestCase
{
    /**
     * @var Client
     */
    private $httpClient;

    public function setUp()
    {
        $this->httpClient = new Client([
            'base_uri' => getenv('CARGONIZER_BASE_URI'),
            'headers' => [
                'X-Cargonizer-Key'    => getenv('CARGONIZER_SECRET_KEY'),
                'X-Cargonizer-Sender' => getenv('CARGONIZER_SENDER'),
                'Content-type'        => 'application/xml'
            ]
        ]); 
    }

    /**
     * @test
     */
    public function consignment_costs_should_receive_an_array_and_convert_to_xml()
    {
        $cargonizer = new CargonizerManager($this->httpClient);
        $cargonizer->consignmentCosts($this->getParams());

        $this->assertXmlStringEqualsXmlString(
            $cargonizer->getXml(),
            '<consignments><consignment transport_agreement="4711"><product>norlines_bil</product>
            <parts><consignee><country>NO</country><postcode>4326</postcode></consignee></parts>
            <items><item amount="1" type="package" weight="12"/></items></consignment></consignments>'
        );
    }

    /**
     * @test
     * 
     * @expectedException \Quicktech\Cargonizer\Exception\ValidatorException
     */
    public function consignment_costs_invalid_params_should_throw_validator_exception()
    {
        $params = $this->getParams();
        unset($params['consignment']['@attributes']);

        $cargonizer = new CargonizerManager($this->httpClient);
        $cargonizer->consignmentCosts($params);
    }

    /**
     * @test
     */
    public function consignment_costs_with_valid_params_should_return_an_array()
    {
        $cargonizer = new CargonizerManager($this->httpClient);
        $cost = $cargonizer->consignmentCosts($this->getParams());

        $this->assertArrayHasKey('estimated_cost', $cost);
        $this->assertArrayHasKey('gross_amount', $cost);
        $this->assertArrayHasKey('net_amount', $cost);
    }

    private function getParams()
    {
        return [
            'consignment' => [
                '@attributes' => [
                    'transport_agreement' => '4711'
                ],
                'product' => 'norlines_bil',
                'parts' => [
                    'consignee' => [
                        'country' => 'NO',
                        'postcode' => '4326'
                    ]
                ],
                'items' => [
                    'item' => [
                        '@attributes' => [
                            'type' => 'package',
                            'amount' => '1',
                            'weight' => '12'
                        ]
                    ]
                ]
            ]
        ];
    }
}