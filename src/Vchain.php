<?php
declare(strict_types=1);

namespace VchainThor;

use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use Comely\Http\Request;

use Comely\Http\Response\CurlResponse;
use Exception;
use VchainThor\Accounts\Account;
use VchainThor\Blocks\Blocks;
use VchainThor\Debug\Debug;
use VchainThor\Logs\Logs;
use VchainThor\Node\Node;
use VchainThor\Subscription\Subscription;
use VchainThor\Exception\VchainThorException;

/**
 * Class Vchain
 * @package VchainThor
 */
class Vchain
{

    /** @var string */
    private string $ip;
    /** @var int */
    private int $port;
    /** @var string|null */
    private ?string $username;
    /** @var string|null */
    private ?string $password;
    /*** @var bool */
    private bool $https;
    /*** @var Account */
    public Account $account;
    /*** @var Blocks */
    public Blocks $block;
    /*** @var Logs */
    public Logs $logs;
    /*** @var Subscription */
    public Subscription $subscription;
    /*** @var Debug|Debug */
    public Debug $debug;

    /**
     * Vchain constructor.
     * @param string $ip
     * @param int $port
     * @param string|null $username
     * @param string|null $password
     * @param bool $https
     */
    public function __construct(string $ip, int $port, ?string $username = "", ?string $password = "", bool $https = false)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->https = $https;
        $this->account = new Account($this);
        $this->block = new Blocks($this);
        $this->logs = new Logs($this);
        $this->node = new Node($this);
        $this->subscription = new Subscription($this);
        $this->debug = new Debug($this);
    }
    //Method To Send HTTP Request

    /**
     * @param string $endpoint
     * @param array $params
     * @param string $httpMethod
     * @return CurlResponse
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function callToCurl(string $endpoint, array $params = [], string $httpMethod = "GET")
    {

        $url = self::generateUrl($endpoint);

        $request = new Request($httpMethod, $url);

        //Set Request Headers
        $request->headers()->set("Content-Type", "application/json")->set("Accept", "application/json");

        //Set Request Body/Params
        if ($params) {
            $request->payload()->use($params);
        }

        $request = $request->curl();

        //Set Basic Authentication
        if ($this->username && $this->password) {

            $request->auth()->basic($this->username, $this->password);
        }

        // Send The Request
        $res = $request->send();
        $errCode = $res->code();

        if ($errCode !== 200) {
            $errMsg = $res->body()->value();

            if ($errMsg) {

                throw new VchainThorException(sprintf('HTTP Response Code %d', $errCode), $errCode);
            }
        }
        return $res;
    }

    //Generate Url

    /**
     * @param string|null $endpoint
     * @return string
     */
    public function generateUrl(?string $endpoint = null): string
    {
        $url = sprintf('%s://%s', $this->https ? "https" : "http", $this->ip);
        if ($this->port) {
            $url .= ":" . $this->port;
        }
        if ($endpoint) {
            $url .= "/" . ltrim($endpoint, "/");
        }
        return $url;

    }

    /**
     * @param array|null $params
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function accounts(?array $params = [])
    {
        return $this->callToCurl("/accounts/*", $params);

    }

    //Get Network Peers

    /**
     * @param array|null $params
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function networkPeers(?array $params = [])
    {
        return $this->callToCurl("/node/network/peers", $params, "GET");

    }
    //Get Account  Code

    /**
     * @param array $queryString
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function accountAddressCode(array $queryString)
    {
        $completeUri = self::generateURI("/accounts/{address}/code", $queryString, ["{address}"]);
        return $this->callToCurl($completeUri, [], "GET");
    }

    //Get Account Storage Value

    /**
     * @param array $queryString
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */

    public function accountAddressStorage($queryString)

    {
        $completeUri = self::generateURI("/accounts/{address}/storage/{key}", $queryString, ["{address}", "{key}"]);

        return $this->callToCurl($completeUri, [], "GET");
    }


    /**
     * @param array $params
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function filtereventlogs(array $params)
    {

        return $this->callToCurl("/logs/event", $params);
    }

    //Access Transactions

    /**
     * @param array $queryString
     * @param array $params
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function transactions(array $queryString, array $params = [])
    {
        $completeUri = self::generateURI("/transactions/{transactionId}", $queryString, ["{transactionId}"]);


        return $this->callToCurl($completeUri, $params, "GET");
    }

    //Get Blocks

    /**
     * @param string $param
     * @return CurlResponse
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function blocks(string $param)
    {
        return $this->callToCurl("/blocks/" . $param, [], "GET");
    }



    //Post Transactions

    /**
     * @param array $params
     * @return CurlResponse|Exception
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     * @throws VchainThorException
     */
    public function postTransactions(array $params = [])
    {

        return $this->callToCurl("/transactions", $params);
    }

    //Post Logs Transfer

    /**
     * @param array $params
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function logsTransfer(array $params)
    {

        return $this->callToCurl("/logs/transfer", $params);
    }

    //Get Node Network Peer

    /**
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function peers()
    {
        return $this->callToCurl("/node/network/peers", [], 'GET');
    }

    //Get subscription Block

    /**
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function subscriptionsBlock()
    {
        return $this->callToCurl("/subscriptions/block", [], 'GET');
    }

    //Get Transection

    /**
     * @param string $params
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function transaction(string $params)
    {
        return $this->callToCurl("/transactions/" . $params, [], 'GET');
    }
    // Receipt

    /**
     * @param string $params
     * @return CurlResponse|Exception
     * @throws VchainThorException
     * @throws HttpRequestException
     * @throws HttpResponseException
     * @throws SSL_Exception
     */
    public function receipt(string $params)
    {
        return $this->callToCurl("/transactions/" . $params . "/receipt");
    }

    //Generate URI

    /**
     * @param string $uri
     * @param array $replaceBy
     * @param array $find
     * @return string
     */
    private function generateURI(string $uri, array $replaceBy, array $find): string
    {
        return str_replace(
            $find,
            $replaceBy,
            $uri
        );

    }
}