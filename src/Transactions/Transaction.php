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

use VchainThor\RLP;

class Transaction
{
    public body $body;

    public function serialize(): RLPEncodedTx
    {
        $rlp = new RLP();
        $txObj = new RLP\RLPObject();
        $txObj->encodeObject($this->body->serialize());
        return new RLPEncodedTx($txObj->getRLPEncoded($rlp));

    }

}

namespace VchainThor\Transactions\Transaction;


namespace VchainThor\Transactions\TxBuilder;

use VchainThor\RLP;
use VchainThor\Transactions\RLPEncodedTx;

class StringEncode
{
    public array $Str;


    public function serialize(bool $withSign = false): RLPEncodedTx
    {
        $rlp = new RLP();
        $txObj = new RLP\RLPObject();
        $arr = ["BUSS", "TYY"];
        $this->Str = $arr;

        $txObj->encodeString($this->Str[0]);
        $txObj->encodeString($this->Str[1]);
//        $txObj->encodeString($StringEncode->Str[2]);
        return new RLPEncodedTx($txObj->getRLPEncoded($rlp));
    }

}
