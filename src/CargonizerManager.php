<?php

namespace Quicktech\Cargonizer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use LSS\Array2XML;
use LSS\XML2Array;
use Quicktech\Cargonizer\Exception\ConsignmentCostException;
use Quicktech\Cargonizer\Util\XMLValidator;
use Quicktech\Cargonizer\Exception\ValidatorException;

/**
 * This class is the main entry point of cargonizer package. Usually the interaction
 * with this class will be done through the Cargonizer Facade
 *
 * @license MIT
 * @package Quicktech\Cargonizer
 */
class CargonizerManager
{
    /**
     * Http client used to connect
     *
     * @var Client
     */
    private $httpClient;

    /**
     * @var string
     */ 
    private $xml;

    /**
     * Create a new confide instance.
     *
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;

        Array2XML::init('1.0', 'UTF-8', false);
    }

    /**
     * Gets the cost estimation for a consignment
     *
     * @param $params
     * @return array
     * @throws ValidatorException|ConsignmentCostException
     */
    public function consignmentCosts($params)
    {
        $xml = Array2XML::createXML('consignments', $params);
        $this->xml = $xml->saveXML();

        $schemaFile = __DIR__ . '/../resources/schemas/consignment.xsd';
        
        try {
            $xmlValidator = new XMLValidator();
            $xmlValidator->validate($this->xml, $schemaFile);

            $response = $this->httpClient->post('consignment_costs.xml', ['body' => $this->xml]);
            $xmlContent = $response->getBody()->getContents();

            return $this->processResponse($xmlContent);
        } catch(ClientException $e) {
            return [];
        } catch(\InvalidArgumentException $e) {
            throw new ValidatorException(
                $e->getMessage()
            );
        }
    }

    private function processResponse($xmlContent)
    {
        $response = [];

        $arrayContent = XML2Array::createArray($xmlContent);
        $consignmentCost = $arrayContent['consignment-cost'];

        foreach ($consignmentCost as $key => $item) {
            $field = str_replace('-','_', $key);
            settype($item['@value'], $item['@attributes']['type']);

            $response[$field] = $item['@value'];
        }

        return $response;
    }

    /**
     * @return string
     */
    public function getXml()
    {
        return $this->xml;
    }
}