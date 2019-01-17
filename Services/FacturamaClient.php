<?php

namespace Jplarar\FacturamaBundle\Services;

use GuzzleHttp\Client;

/**
 * Class FacturamaClient
 * @package Jplarar\FacturamaBundle\Services
 */
class FacturamaClient
{
    // Environment
    const PROD_SERVER = "https://api.facturama.mx";
    const DEV_SERVER = "https://apisandbox.facturama.mx";

    // Routes
    const CREATE_BILL = "/2/cfdis";
    const GET_BILL = "/cfdi/";
    const GET_CLIENT = "/Client?keyword=";
    const NEW_CLIENT = "/Client";

    // Auth
    protected $user;
    protected $password;

    // Server
    protected $server;

    // Variables
    protected $serie;
    protected $currency;
    protected $expeditionPlace;
    protected $cfdiUse = "G03";
    protected $paymentForm = "04";
    protected $productCode;
    protected $unitCode;
    protected $taxes = 0.16;
    protected $products;

    /**
     * FacturamaClient constructor.
     * @param $facturama_username
     * @param $facturama_password
     * @param $serie
     * @param $currency
     * @param $expeditionPlace
     * @param $cfdiUse
     * @param $paymentForm
     * @param $productCode
     * @param $unitCode
     * @param $taxes
     * @param string $env
     */
    public function __construct(
        $facturama_username,
        $facturama_password,
        $serie,
        $currency,
        $expeditionPlace,
        $cfdiUse,
        $paymentForm,
        $productCode,
        $unitCode,
        $taxes,
        $env = "dev"
    )
    {
        $this->user = $facturama_username;
        $this->password = $facturama_password;
        $this->server = ($env == "dev") ? self::DEV_SERVER : self::PROD_SERVER;

        $this->serie = $serie;
        $this->currency = $currency;
        $this->expeditionPlace = $expeditionPlace;
        $this->cfdiUse = $cfdiUse;
        $this->paymentForm = $paymentForm;
        $this->productCode = $productCode;
        $this->unitCode = $unitCode;
        $this->taxes = $taxes;
    }

    /**
     * @param $folio
     * @param $rfc
     * @param $name
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createBill($folio, $rfc, $name)
    {
        $body = [
            "Serie"=> $this->serie,
            "Currency" => $this->currency,
            "ExpeditionPlace" => $this->expeditionPlace,
            "Folio" => intval($folio),
            "CfdiType" => "I",
            "PaymentForm" => $this->paymentForm,
            "PaymentMethod" => "PUE",
            "Receiver" => [
                "Rfc"=> $rfc,
                "Name" => $name,
                "CfdiUse"=> $this->cfdiUse
            ],
            "Items"=> $this->products
        ];

        $client = new Client();

        $response = $client->post(
            $this->server.self::CREATE_BILL,
            [
                \GuzzleHttp\RequestOptions::AUTH => [$this->user, $this->password],
                \GuzzleHttp\RequestOptions::JSON => $body
            ]
        );

        $this->products = [];

        return $response->getBody()->getContents();
    }

    /**
     * @param $description
     * @param $price
     * @param $quantity
     * @param $subTotal
     */
    public function addProduct($description, $price, $quantity)
    {
        $this->products[] = [
            "ProductCode"=> $this->productCode,
            "Description"=> $description,
            "UnitCode"=> $this->unitCode,
            "UnitPrice"=> number_format($price, 2, '.', ''),
            "Quantity"=> $quantity,
            "Subtotal"=> number_format($quantity * $price, 2, '.', ''),
            "Taxes"=> [
                [
                    "Total"=> number_format(($quantity * $price) * $this->taxes, 2, '.', ''),
                    "Name"=> "IVA",
                    "Base"=> number_format($quantity * $price, 2, '.', ''),
                    "Rate"=> $this->taxes,
                    "IsRetention"=> false
                ]
            ],
            "Total"=> number_format((1 + $this->taxes) * ($quantity * $price), 2, '.', '')
        ];
    }

    /**
     * @param $id
     * @param string $format
     * @return mixed
     */
    public function getBill($id, $format = 'pdf')
    {
        $url = $this->server.self::GET_BILL.$format.'/issued/'.$id;
        $client = new Client();
        $response = $client->get(
            $url,
            [
                \GuzzleHttp\RequestOptions::AUTH => [$this->user, $this->password]
            ]
        );

        return $response->getBody()->getContents();
    }

    /**
     * @param $rfc
     * @return mixed
     */
    public function getClient($rfc)
    {
        $url = $this->server.self::GET_CLIENT.$rfc;
        $client = new Client();
        $response = $client->get(
            $url,
            [
                \GuzzleHttp\RequestOptions::AUTH => [$this->user, $this->password]
            ]
        );

        return $response->getBody()->getContents();
    }

    /**
     * @param $email
     * @param $rfc
     * @param $name
     * @return mixed
     */
    public function newClient($email, $rfc, $name)
    {
        $body = [
            "Email"=> $email,
            "Rfc" => $rfc,
            "Name" => $name,
            "CfdiUse" => $this->cfdiUse,
            "TaxResidence" => '',
            "NumRegIdTrib" => ''
        ];
        $client = new Client();
        $response = $client->post(
            $this->server.self::NEW_CLIENT,
            [
                \GuzzleHttp\RequestOptions::AUTH => [$this->user, $this->password],
                \GuzzleHttp\RequestOptions::JSON => $body
            ]
        );

        return $response->getBody()->getContents();
    }

}