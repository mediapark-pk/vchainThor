<?php
declare(strict_types=1);

namespace VchainThor\Clause;


use VchainThor\Clause\clauseBody;
use VchainThor\Exception\IncompleteTxException;
use VchainThor\RLP;

/**
 * Class Clause
 * @package VchainThor\Clause
 */
class Clause
{
    /** @var \VchainThor\Clause\clauseBody */
    public clauseBody $body;

    /**
     * Clause constructor.
     * @param \VchainThor\Clause\clauseBody $body
     */
    public function __construct(clauseBody $body)
    {
        $this->body = $body;
    }

    /**
     * @return RLP\RLPObject
     * @throws IncompleteTxException
     */
    public function serialize(): RLP\RLPObject
    {
        $clauseRlpObj = new RLP\RLPObject();

        //To
        if (!isset($this->body->to) || $this->body->to < 0) {
            throw new IncompleteTxException('To  value is not set or is invalid');
        }
        $clauseRlpObj->encodeHexString($this->body->to);

        //Value
        if (!isset($this->body->value) || $this->body->value < 0) {
            throw new IncompleteTxException('Value is not set or is invalid');
        }
        $clauseRlpObj->encodeInteger($this->body->value);

        return $clauseRlpObj;
    }

}