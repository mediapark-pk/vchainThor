<?php
declare(strict_types=1);

namespace VchainThor\Transaction;


use VchainThor\Exception\IncompleteTxException;
use VchainThor\RLP;
use VchainThor\Transactions\RLPEncodedTx;

class Clause
{
    public string $to;
    public int $value;
    public array $data;

    /**
     * Clauses constructor.
     * @param string $to
     * @param int $value
     * @param array $data
     */
    public function __construct(string $to, int $value, array $data = [])
    {
        $this->to = $to;
        $this->value = $value;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return RLPEncodedTx
     * @throws IncompleteTxException
     */
    public function serialize(): RLPEncodedTx
    {
        $rlp = new RLP();
        $txObj = new RLP\RLPObject();

        //To
        if (!isset($this->to) || $this->to < 0) {
            throw new IncompleteTxException('To  value is not set or is invalid');
        }
        $txObj->encodeHexString($this->to);

        //Value
        if (!isset($this->value) || $this->value < 0) {
            throw new IncompleteTxException('Value is not set or is invalid');
        }
        $txObj->encodeInteger($this->value);
    }

}