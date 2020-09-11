<?php

declare(strict_types=1);

namespace VchainThor\Transaction;


use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use VchainThor\Exception\VchainThorException;
use VchainThor\Exception\VchainThorTransactionException;
use VchainThor\Vchain;

class TransactionApi
{

    /*** @var Vchain */
    public Vchain $vchain;

    /**
     * TransactionApi constructor.
     * @param Vchain $vchain
     */
    public function __construct(Vchain $vchain)
    {
        $this->vchain = $vchain;
    }

    /**
     * @param string $raw
     * @return string
     * @throws HttpRequestException
     * @throws VchainThorTransactionException|VchainThorException
     */
    public function createTransaction(string $raw): string
    {
        $endpoint = "/transactions";
        $payload = ["raw" => $raw];
        $response = $this->vchain->callToCurl($endpoint, $payload, "POST");
        $responseId = $response->payload()->get("id");
        if ($responseId) {
            return $responseId;
        }
        throw new VchainThorTransactionException("Nohting Found!");

    }

    /**
     * @param string $id
     * @return Transaction
     * @throws HttpRequestException
     * @throws VchainThorException
     * @throws VchainThorTransactionException
     */
    public function getTransaction(string $id): Transaction
    {
        $endpoint = sprintf("/transactions/%s", $id);
        $response = $this->vchain->callToCurl($endpoint);
        if ($response->payload()->get('id')) {
            return new Transaction();
        }
        throw new VchainThorTransactionException("No, Transaction Found!");
    }

    /**
     * @param string $id
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainThorException
     * @throws VchainThorTransactionException
     */
    public function getTransactionReceipt(string $id): array
    {
        $endpoint = sprintf("/transactions/%s/receipt", $id);
        $response = $this->vchain->callToCurl($endpoint);
        if ($response->payload()->get('id')) {
            return $response->payload()->array();
        }
        throw new VchainThorTransactionException("No, Transacion Found");

    }
}