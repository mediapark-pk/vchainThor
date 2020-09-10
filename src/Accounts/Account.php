<?php

declare(strict_types=1);

namespace VchainThor\Accounts;


use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use VchainThor\Exception\VchainThorAccountException;
use VchainThor\Exception\VchainThorException;
use VchainThor\Vchain;

/**
 * Class Account
 * @package VchainThor\Addresses
 */
class Account
{

    /*** @var Vchain */
    public Vchain $vchain;

    /**
     * Account constructor.
     * @param Vchain $vchain
     */
    public function __construct(Vchain $vchain)
    {
        $this->vchain = $vchain;
    }

    public function accounts(string $key): array
    {
        $payload = ["key" => $key];
        $endpoint = sprintf("/accounts/*");
        $response = $this->vchain->callToCurl($endpoint, $payload, "POST");
        print_r($response);
        die();
        if ($response->payload()->get('balance') || $response->payload()->get('energy')) {
            return $address = [
                "balance" => $response->payload()->get('balance'),
                "energy" => $response->payload()->get('energy'),
                "hasCode" => $response->payload()->get('hasCode')
            ];
        } else {
            throw new VchainThorAccountException("No,Response Found");
        }

    }

    /**
     * @param string $address
     * @param string|null $revision
     * @return array
     * @throws VchainThorAccountException
     * @throws HttpRequestException
     * @throws VchainThorException
     */
    public function accountDetails(string $address, ?string $revision = null): array
    {
        $endpoint = sprintf("/accounts/%s?revision=%s", $address, $revision);
        $response = $this->vchain->callToCurl($endpoint);
        if ($response->payload()->get('balance') || $response->payload()->get('energy')) {
            return $address = [
                "balance" => $response->payload()->get('balance'),
                "energy" => $response->payload()->get('energy'),
                "hasCode" => $response->payload()->get('hasCode')
            ];
        } else {
            throw new VchainThorAccountException("No,Response Found");
        }


    }

    /**
     * @param string $address
     * @param string|null $revision
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainThorAccountException
     * @throws VchainThorException
     */
    public function retrieveAccountCode(string $address, ?string $revision = null): array
    {
        $endpoint = sprintf("/accounts/%s/code?revision=%s", $address, $revision);
        $response = $this->vchain->callToCurl($endpoint);
        if ($response->payload()->get('code')) {
            return $code = ["code" => $response->payload()->get('code')];
        } else {
            throw new VchainThorAccountException("No,Code Found");
        }
    }


    /**
     * @param string $address
     * @param string $key
     * @param string|null $revision
     * @return string
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainThorAccountException
     * @throws VchainThorException
     */
    public function retrieveAccountValue(string $address, string $key, ?string $revision = null): string
    {
        $endpoint = sprintf("/accounts/%s/storage/%s/code?revision=%s", $address, $key, $revision);
        $response = $this->vchain->callToCurl($endpoint);
        if ($response->payload()->get('value')) {
            return $value = $response->payload()->get('value');
        } else {
            throw new VchainThorAccountException("No,Code Found");
        }
    }


}