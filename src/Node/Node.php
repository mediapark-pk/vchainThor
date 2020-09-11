<?php

declare(strict_types=1);

namespace VchainThor\Node;


use VchainThor\Exception\VchainThorException;
use VchainThor\Vchain;

/**
 * Class Node
 * @package VchainThor\Node
 */
class Node
{
    /*** @var Vchain */
    public Vchain $vchain;


    /**
     * Node constructor.
     * @param Vchain $vchain
     */
    public function __construct(Vchain $vchain)
    {
        $this->vchain = $vchain;
    }

    /**
     * @return array
     */
    public function getConnectedPeers(): array
    {
        $endpoint = "/node/network/peers";
        $response = $this->vchain->callToCurl($endpoint);
        if ($response->payload()) {
            return $response->payload()->array();
        } else {
            throw new VchainThorException("Nothing Found");
        }
    }

}