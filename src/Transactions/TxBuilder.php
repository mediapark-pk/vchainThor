<?php
/*
 * This file is a part of "furqansiddiqui/ethereum-php" package.
 * https://github.com/furqansiddiqui/ethereum-php
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/furqansiddiqui/ethereum-php/blob/master/LICENSE
 */

declare(strict_types=1);

namespace VchainThor\Transactions;

use Comely\DataTypes\Buffer\Base16;
use FurqanSiddiqui\ECDSA\Signature\Signature;
use VchainThor\Accounts\Account;
use VchainThor\Exception\IncompleteTxException;

use VchainThor\RLP;
use VchainThor\Transaction\Clause;
use VchainThor\Transaction\Reserved;


/**
 * Class TxBuilder
 * @package VchainThor\Transactions
 */
class TxBuilder
{
    /** @var string */
    private string $chainTag;

    /** @var int */
    private int $blockRef;
    /** @var integer */
    private int $expiration;

    /** @var Clause */
    private Clause $clauses;

    /** @var integer */
    private int $gasPriceCoef;

    /** @var integer */
    private int $gas;

    /** @var int */
    private int $nonce;

//    /** @var Signature|null */
//    private ?Signature $signature;


    /** @var string|null */
    private ?string $dependsOn;

    /** @var Reserved */
    private Reserved $reserved;


    private array $signature = [
        "v" => 0,
        "r" => "",
        "s" => "",
    ];

    /**
     * @param Ethereum $eth
     * @param RLPEncodedTx $encoded
     * @return static
     * @throws \FurqanSiddiqui\Ethereum\Exception\AccountsException
     */
    public static function Decode(RLPEncodedTx $encoded): self
    {
        $decoder = new RLP\RLPDecoder($encoded->serialized()->hexits(false));
        $decoder
            ->expectString(0, "chainTag")
            ->expectInteger(1, "nonce")
            ->expectInteger(2, "blockRef");
//        $decoder->expectInteger(0, "nonce")
//            ->expectInteger(1, "gasPrice")
//            ->expectInteger(2, "gasLimit")
//            ->mapValue(3, "to")
//            ->expectInteger(4, "value")
//            ->mapValue(5, "data")
//            ->expectInteger(6, "signatureV")
//            ->mapValue(7, "signatureR")
//            ->mapValue(8, "signatureS");

        $decoded = $decoder->decode();
        print_r($decoded);
        die();

        $tx = new self($eth);
        $tx->nonce($decoded["nonce"])
            ->gas($eth->wei()->fromWei($decoded["gasPrice"]), intval($decoded["gasLimit"]))
            ->to($eth->getAccount($decoded["to"]))
            ->value($eth->wei()->fromWei($decoded["value"]))
            ->signature(
                $decoded["signatureV"],
                new Base16($decoded["signatureR"]),
                new Base16($decoded["signatureS"])
            );

        return $tx;
    }


    /**
     * TxBuilder constructor.
     */
    public function __construct()
    {
        $this->signature["v"] = 1;
    }

    /**
     * @param string $chainTag
     * @return TxBuilder
     */
    public function setChainTag(string $chainTag): TxBuilder
    {
        $this->chainTag = $chainTag;
        return $this;
    }

    /**
     * @param int $blockRef
     * @return TxBuilder
     */
    public function setBlockRef(int $blockRef): TxBuilder
    {
        $this->blockRef = $blockRef;
        return $this;
    }

    /**
     * @param int $expiration
     * @return TxBuilder
     */
    public function setExpiration(int $expiration): TxBuilder
    {
        $this->expiration = $expiration;
        return $this;
    }

    /**
     * @param Clause $clauses
     * @return TxBuilder
     */
    public function setClauses(Clause $clauses): TxBuilder
    {
        $this->clauses = $clauses;
        return $this;
    }

    /**
     * @param int $gasPriceCoef
     * @return TxBuilder
     */
    public function setGasPriceCoef(int $gasPriceCoef): TxBuilder
    {
        $this->gasPriceCoef = $gasPriceCoef;
        return $this;
    }

    /**
     * @param int $gas
     * @return TxBuilder
     */
    public function setGas(int $gas): TxBuilder
    {
        $this->gas = $gas;
        return $this;
    }

    /**
     * @param int $nonce
     * @return TxBuilder
     */
    public function setNonce(int $nonce): TxBuilder
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @param string|null $dependsOn
     * @return TxBuilder
     */
    public function setDependsOn(?string $dependsOn): TxBuilder
    {
        $this->dependsOn = $dependsOn;
        return $this;
    }

    /**
     * @param Reserved $reserved
     * @return TxBuilder
     */
    public function setReserved(Reserved $reserved): TxBuilder
    {
        $this->reserved = $reserved;
        return $this;
    }

    /**
     * @param array $signature
     * @return TxBuilder
     */
    public function setSignature(array $signature): TxBuilder
    {
        $this->signature = $signature;
        return $this;
    }


    /**
     * @param int $v
     * @param Base16|null $r
     * @param Base16|null $s
     * @return $this
     */
    public function signature(int $v, ?Base16 $r = null, ?Base16 $s = null): self
    {
        $this->signature["v"] = $v;
        $this->signature["r"] = $r ? $r->value() : "";
        $this->signature["s"] = $s ? $s->value() : "";
        return $this;
    }

    /**
     * @return RLPEncodedTx
     * @throws IncompleteTxException
     */
    public function serialize(): RLPEncodedTx
    {
        $rlp = new RLP();
        $txObj = new RLP\RLPObject();

        //Chain Tag
        if (!isset($this->chainTag) || $this->chainTag < 0) {
            throw new IncompleteTxException('Chain Tag value is not set or is invalid');
        }
        $txObj->encodeHexString($this->chainTag);

        //BlockRef
        if (!isset($this->blockRef) || $this->blockRef < 0) {
            throw new IncompleteTxException('Block Ref value is not set or is invalid');
        }
        $txObj->encodeInteger($this->blockRef);

        //Expiration
        if (!isset($this->expiration) || $this->expiration < 0) {
            throw new IncompleteTxException('Expiration value is not set or is invalid');
        }
        $txObj->encodeInteger($this->expiration);

        //Clauses
        if (!isset($this->clauses) || $this->clauses < 0) {
            throw new IncompleteTxException('Clause value is not set or is invalid');
        }
//        $txObj->encodeHexString($this->clauses);

//        // Gas
//        if (!isset($this->gas) || $this->gas < 1) {
//            throw new IncompleteTxException('Gas  are not defined');
//        }
//
//        $txObj->encodeInteger($this->gas);
//
//        //Gas Price Coefficient
//        if (!isset($this->gasPriceCoef) || $this->gasPriceCoef < 0) {
//            throw new IncompleteTxException('Gas Price Coefficient value is not set or is invalid');
//        }
//        $txObj->encodeInteger($this->gasPriceCoef);
//
//        // Nonce
//        if (!isset($this->nonce) || $this->nonce < 0) {
//            throw new IncompleteTxException('Nonce value is not set or is invalid');
//        }
//
//        $txObj->encodeInteger($this->nonce);


//
//        // To
//        if (!isset($this->to)) {
//            throw new IncompleteTxException('To/Payee address is not set');
//        }
//
//        $txObj->encodeHexString($this->to->getAddress());
//
//
//        // Signature
//        $txObj->encodeInteger($this->signature["v"]);
//        $txObj->encodeHexString($this->signature["r"]);
//        $txObj->encodeHexString($this->signature["s"]);

        print_r($rlp);exit();
        return new RLPEncodedTx($txObj->getRLPEncoded($rlp));
    }
}
