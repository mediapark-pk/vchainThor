<?php
declare(strict_types=1);

namespace VchainThor\Subscription;


use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use VchainThor\Exception\VchainThorException;
use VchainThor\Vchain;

/**
 * Class Subscription
 * @package VchainThor\Subscription
 */
class Subscription
{


    /*** @var Vchain */
    public Vchain  $vchain;

    /**
     * Subscription constructor.
     */
    public function __construct(Vchain $vchain)
    {
        $this->vchain = $vchain;
    }

    /**
     * @return array
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     */
    public function subscribeNewBlock()
    {
        $endpoint = "/subscriptions/block";
        $response = $this->vchain->callToCurl($endpoint);
        if ($response) {

            return $response->payload()->array();
        } else {
            throw new VchainThorException("Nothing Found");
        }
    }

    /**
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainThorException
     */
    public function subscribeNewEvent()
    {
        $endpoint = "/subscriptions/event";
        $response = $this->vchain->callToCurl($endpoint);
        if ($response) {

            return $response->payload()->array();
        } else {
            throw new VchainThorException("Nothing Found");
        }
    }

    /**
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainThorException
     */
    public function subscribeNewTransfer()
    {
        $endpoint = "/subscriptions/transfer";
        $response = $this->vchain->callToCurl($endpoint);
        if ($response) {

            return $response->payload()->array();
        } else {
            throw new VchainThorException("Nothing Found");
        }
    }

    /**
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainThorException
     */
    public function subscribeChainBeat()
    {
        $endpoint = "/subscriptions/beat";
        $response = $this->vchain->callToCurl($endpoint);
        if ($response) {

            return $response->payload()->array();
        } else {
            throw new VchainThorException("Nothing Found");
        }

    }

}