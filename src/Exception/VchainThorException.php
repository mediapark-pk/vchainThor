<?php


declare(strict_types=1);

namespace VchainThor\Exception;

use VchainThor\Exception\VchainThorRPCException;

/**
 * Class VchainThorException
 * @package VchainThor\Exception
 */
class VchainThorException extends VchainThorRPCException
{
    /**
     * @param string $method
     * @param string $expected
     * @param string $got
     * @return VchainThorException
     */
    public static function unexpectedResultType(string $method, string $expected, string $got): self
    {
        return new self(
            sprintf('Method [%s] expects result type %s, got %s', $method, strtoupper($expected), strtoupper($got))
        );
    }
}
