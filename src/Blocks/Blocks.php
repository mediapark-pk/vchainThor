<?php
declare(strict_types=1);

namespace VchainThor\Blocks;

use VchainThor\Exception\VchainThorException;
use VchainThor\Vchain;

/**
 * Class Blocks
 * @package VchainThor\Blocks
 */
class Blocks
{
    /*** @var Vchain */
    public Vchain $vchain;

    /**
     * Blocks constructor.
     * @param Vchain $vchain
     */
    public function __construct(Vchain $vchain)
    {
        $this->vchain = $vchain;
    }

    /**
     * @param string $revision
     * @param bool $expanded
     * @return BlockResponse
     * @throws VchainThorException
     */
    public function getBlock(string $revision, bool $expanded = false): BlockResponse
    {
        $endpoint = sprintf("/blocks/%s?expanded=%s", $revision, $expanded);
        $response = $this->vchain->callToCurl($endpoint);
        print_r($response);
        die();
        return new BlockResponse($response);
    }
}