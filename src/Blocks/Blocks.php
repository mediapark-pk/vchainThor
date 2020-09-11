<?php
declare(strict_types=1);

namespace VchainThor\Blocks;

use VchainThor\Exception\VchainThorBlockException;
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
        $data = $response->payload();
        if ($data->get('id')) {
            return new BlockResponse(
                $data->get('number'),
                $data->get('id'),
                $data->get('size'),
                $data->get('parentid'),
                $data->get('timestamp'),
                $data->get('gaslimit'),
                $data->get('beneficiary'),
                $data->get('gasused'),
                $data->get('totalscore'),
                $data->get('txsroot'),
                $data->get('txsfeatures'),
                $data->get('stateroot'),
                $data->get('receiptsroot'),
                $data->get('signer'),
                $data->get('istrunk'),
                $data->get('transactions')
            );

        } else {
            throw new VchainThorBlockException("Block Data  Not Found!");
        }
    }
}