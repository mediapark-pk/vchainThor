<?php
declare(strict_types=1);

namespace VchainThor\Logs;


use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use VchainThor\Exception\VchainThorException;
use VchainThor\Vchain;

/**
 * Class Logs
 * @package VchainThor\Logs
 */
class Logs
{
    /*** @var Vchain */
    public Vchain $vchain;

    /**
     * Logs constructor.
     * @param Vchain $vchain
     */
    public function __construct(Vchain $vchain)
    {
        $this->vchain = $vchain;
    }

    /**
     * @return array
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainThorException
     */
    public function getEventLogs(?int $from = null, ?int $to = null, int $offset, int $limit, string $address, array $topic, ?string $order = null): array
    {
        if ($topic && is_string(key($topic)[0])) {
            throw new VchainThorException("Topic Cannot Be Associative Array");
        }


        $payload = [];
        $payload["range"] = ["unit" => "block", "from" => $from, "to" => $to];
        $payload["options"] = ["offset" => $offset, "limit" => $limit];
        $payload["criteriaSet"][0] = ["address" => $address];

        if ($topic) {
            for ($i = 0; $i < count($topic); $i++) {
                $payload["criteriaSet"][0]["topic" . $i] = $topic[$i];
            }
        }

        $endpoint = "/logs/event";
        $response = $this->vchain->callToCurl($endpoint, $payload, "POST");
        if ($response->payload()) {
            return ($response->payload()->array());
        } else {
            throw new VchainThorException("Empty Output");
        }
    }

    public function getTransferLogs(?int $from = null, ?int $to = null, int $offset, int $limit, string $txOrigin, string $sender, string $recipient, ?string $order = null): array
    {
        $payload = [];
        $payload["range"] = ["unit" => "block", "from" => $from, "to" => $to];
        $payload["options"] = ["offset" => $offset, "limit" => $limit];
        $payload["criteriaSet"][0] = ["txOrigin" => $txOrigin];
        $payload["criteriaSet"][0] = ["sender" => $sender];
        $payload["criteriaSet"][0] = ["recipient" => $recipient];

        $endpoint = "/logs/transfer";
        $response = $this->vchain->callToCurl($endpoint, $payload, "POST");
        if ($response->payload()) {
            return ($response->payload()->array());
        } else {
            throw new VchainThorException("Empty Output");
        }

    }

}