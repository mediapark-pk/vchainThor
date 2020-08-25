<?php
declare(strict_types=1);

namespace VchainThor;

use Comely\Http\Request;
use Exception;

class Vchain
{

    /** @var string */
    private string $ip;
    /** @var int */
    private int $port;
    /** @var string */
    private string $username;
    /** @var string */
    private string $password;


    /**
     * Params constructor.
     * @param string $ip
     * @param int $port
     * @param string $username
     * @param string $password
     */
    public function __construct(string $ip, int $port, ?string $username = "", ?string $password = "")
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }


    //Method To Send HTTP Request
    
    /**
     * @param string $queryString
     * @param array $params
     * @param string|string $httpMethod
     * @return \Comely\Http\Response\CurlResponse|Exception
     * @throws \Comely\Http\Exception\HttpRequestException
     * @throws \Comely\Http\Exception\HttpResponseException
     * @throws \Comely\Http\Exception\SSL_Exception
     */
    private function callToCurl(string $queryString, array $params = [], string $httpMethod = "POST")
    {
        try {
            $url = self::generateUrl($this->ip, $this->port);
        } catch (Exception $e) {
            return $e;

        }
        //Set Complete Url
        $url .= $queryString;

        $request = new Request($httpMethod, $url);

        //Set Request Headers
        $request
            ->headers()
            ->set("Content-Type", "application/json")
            ->set("Accept", "application/json");

        //Set Request Body/Params
        $params ? $request->payload()->use($params) : null;

        $request = $request->curl();

        //Set Basic Authentication
//        $request->auth()->basic($this->username, $this->password);

        //Send The Request
        $response = $request->send();


        return $response;

    }

    //Generate Url
    public function generateUrl(string $ip, int $port): string
    {
        /*Port Checking */
        if (!is_numeric($port)) {
            throw new Exception("A port can only be a number", 1);

        }
        return $ip . ":" . $port;
    }

    public function accounts(?array $params = [])
    {
        return $this->callToCurl("/accounts/*", $params);

    }

    //Get Network Peers
    public function networkPeers(?array $params = [])
    {
        return $this->callToCurl("/node/network/peers", $params, "GET");

    }

    //Get Account  Code
    public function accountAddressCode(array $queryString)
    {
        $completeUri = self::generateURI("/accounts/{address}/code", $queryString, ["{address}"]);
        return $this->callToCurl($completeUri, [], "GET");
    }


    //Get Account Storage Value
    public function accountAddressStorage(array $queryString)
    {
        $completeUri = self::generateURI("/accounts/{address}/storage/{key}", $queryString, ["{address}", "{key}"]);

        return $this->callToCurl($completeUri, [], "GET");
    }


    public function blocks(string $param)
    {
        return $this->callToCurl("/blocks/" . $param, [], "GET");
    }

    public function filtereventlogs(array $params)
    {

        return $this->callToCurl("/logs/event", $params);
    }

    //Access Transactions

    public function transactions(array $queryString, array $params = [])
    {
        $completeUri = self::generateURI("/transactions/{id}", $queryString, ["{id}"]);

        return $this->callToCurl($completeUri, $params, "GET");
    }


    /**
     * @param string $uri
     * @param array $replaceBy
     * @param array $find
     * @return string
     */
    private function generateURI(string $uri, array $replaceBy, array $find): string
    {
        $result = str_replace(
            $find,
            $replaceBy,
            $uri
        );
        return $result;

    }
}