<?php
declare(strict_types=1);

namespace VchainThor\Debug;


use VchainThor\Exception\VchainThorException;
use VchainThor\Vchain;

class Debug
{
    public Vchain $vchain;

    /**
     * Debug constructor.
     * @param Vchain $vchain
     */
    public function __construct(Vchain $vchain)
    {
        $this->vchain = $vchain;
    }

    /**
     * @param string $name
     * @param string $target
     * @throws \Comely\Http\Exception\HttpRequestException
     * @throws \Comely\Http\Exception\HttpResponseException
     * @throws \Comely\Http\Exception\SSL_Exception
     * @throws \VchainThor\Exception\VchainThorException
     */
    public function createTracer(?string $name=null, string $target): array
    {
        $endpoint = "/debug/tracers";
        $payload = ["name" => $name, "target" => $target];
        $response = $this->vchain->callToCurl($endpoint, $payload, "POST");
        if ($response->payload()->array()) {
            return $response->payload()->array();
        }
        throw new VchainThorException("Nothing Found!");

    }

    /**
     * @param string $address
     * @param string $keyStart
     * @param string $target
     * @param int $result
     * @return array
     * @throws VchainThorException
     */
    public function debugStorageRange(string $address, string $keyStart, string $target, int $result = 10): array
    {
        $endpoint = "/debug/storage-range";
        $payload = ["address" => $address, "keyStart" => $keyStart, "target" => $target, "maxResult" => $result];
        $response = $this->vchain->callToCurl($endpoint, $payload, "POST");
        $data = $response->payload()->array();
        if ($data) {
            return $data;
        }
        throw new VchainThorException("Nothing Found");

    }

}