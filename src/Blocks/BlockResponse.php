<?php


namespace VchainThor\Blocks;


use VchainThor\Vchain;

class BlockResponse
{
    public int $number;
    public string $id;
    public int $size;
    public string $parentID;
    public int $timestamp;
    public int $gasLimit;
    public string $beneficiary;
    public int $gasUsed;
    public int $totalScore;
    public string $txsRoot;
    public int $txsFeatures;
    public string $stateRoot;
    public string $receiptsRoot;
    public string $signer;
    public bool $isTrunk;
    public array $transactions;
    /*** @var Vchain */
    public Vchain $vchain;

    /**
     * Blocks constructor.
     * @param int $number
     * @param string $id
     * @param int $size
     * @param string $parentID
     * @param int $timestamp
     * @param int $gasLimit
     * @param string $beneficiary
     * @param int $gasUsed
     * @param int $totalScore
     * @param string $txsRoot
     * @param int $txsFeatures
     * @param string $stateRoot
     * @param string $receiptsRoot
     * @param string $signer
     * @param bool $isTrunk
     * @param array $transactions
     */
    public function __construct(int $number, string $id, int $size, string $parentID, int $timestamp, int $gasLimit, string $beneficiary, int $gasUsed, int $totalScore, string $txsRoot, int $txsFeatures, string $stateRoot, string $receiptsRoot, string $signer, bool $isTrunk, array $transactions)
    {
        $this->number = $number;
        $this->id = $id;
        $this->size = $size;
        $this->parentID = $parentID;
        $this->timestamp = $timestamp;
        $this->gasLimit = $gasLimit;
        $this->beneficiary = $beneficiary;
        $this->gasUsed = $gasUsed;
        $this->totalScore = $totalScore;
        $this->txsRoot = $txsRoot;
        $this->txsFeatures = $txsFeatures;
        $this->stateRoot = $stateRoot;
        $this->receiptsRoot = $receiptsRoot;
        $this->signer = $signer;
        $this->isTrunk = $isTrunk;
        $this->transactions = $transactions;

    }

}