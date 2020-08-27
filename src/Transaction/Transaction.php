<?php

declare(strict_types=1);

namespace VchainThor\Transaction;


class Transaction
{
    /** @var string */
    private string $chainTag;

    /** @var integer */
    private int $blockRef;
    /** @var integer */
    private int $expiration;

    /** @var Clauses */
    private Clauses $clauses;

    /** @var integer */
    private int $gasPriceCoef;

    /** @var integer */
    private int $gas;

    /** @var integer */
    private int $nonce;

    /** @var string */
    private string $signature;

    /** @var string */
    private string $dependsOn;

    /** @var string */
    private Reserved $reserved;

    /**
     * Transaction constructor.
     * @param string $chainTag
     * @param int $blockRef
     * @param int $expiration
     * @param Clauses $clauses
     * @param int $gasPriceCoef
     * @param int $gas
     * @param int $nonce
     * @param string $signature
     * @param string $dependsOn
     * @param string|Reserved $reserved
     */
    public function __construct(string $chainTag, int $blockRef, int $expiration, Clauses $clauses, int $gasPriceCoef, int $gas, int $nonce, string $signature, string $dependsOn, $reserved)
    {
        $this->chainTag = $chainTag;
        $this->blockRef = $blockRef;
        $this->expiration = $expiration;
        $this->clauses = $clauses;
        $this->gasPriceCoef = $gasPriceCoef;
        $this->gas = $gas;
        $this->nonce = $nonce;
        $this->signature = $signature;
        $this->dependsOn = $dependsOn;
        $this->reserved = $reserved;
    }
}