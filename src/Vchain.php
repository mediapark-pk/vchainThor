<?php
declare(strict_types=1);

namespace VchainThor;

use Comely\Http\Request;
use Exception;

class Vchain
{

    /** @var string */
    public string $ip;
    /** @var int */
    public int $port;
    /** @var string */
    public string $username;
    /** @var string */
    public string $password;
    /** @var int id */
    public int $id = 1;
    /** @var string $jsonRpc */
    public string $jsonRpc = "2.0";

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


    /*Method To Send HTTP Request*/
    private function callToCurl(string $queryString, array $params=[], string $httpMethod = "POST")
    {
        try {
            $url = self::generateUrl($this->ip, $this->port);
        } catch (Exception $e) {
            return $e;

        }
        /*Set Complete Url*/
        $url .= $queryString;

        $request = new Request($httpMethod, $url);

        /*Set Request Headers*/
        $request
            ->headers()
            ->set("Content-Type", "application/json")
            ->set("Accept", "application/json");

        /*Set Request Body/Params*/
        $params ? $request->payload($params) : null;


        $request = $request->curl();

        /*Set Basic Authentication*/
//        $request->auth()->basic($this->username, $this->password);

        /*Send The Request*/
        $response = $request->send();

        return $response;

    }

    /*Generate Url*/
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

    /*Get Network Peers*/
    public function networkPeers(?array $params = [])
    {
        return $this->callToCurl("/node/network/peers", $params, "GET");

    }
    /*Get Account Address Code*/
    public function accountAddressCode()
    {
        return $this->callToCurl("/accounts/0x5034aa590125b64023a0262112b98d72e3c8e40e/code",[],"GET");
    }

    public function blocks(string $param){
        return $this->callToCurl("/blocks/".$param,[],"GET");
    }

    public function filtereventlogs(array $params){

        return $this->callToCurl("/logs/event",$params);
    }



}