<?php

declare(strict_types=1);

namespace VchainThor\Transaction;

use Comely\DataTypes\Buffer\Base16;
use Comely\DataTypes\Buffer\Binary;
use deemru\Blake2b;
use Web3p\RLP\RLP;
use  FurqanSiddiqui\ECDSA\Curves\Secp256k1;
use FurqanSiddiqui\ECDSA\Signature\Signature;
use Web3p\RLP\Types\Str;
use VchainThor\Transaction\Reserved;
use VchainThor\Transaction\Clause;

class Transaction
{
    /** @var string */
    private string $chainTag;

    /** @var string */
    private string $blockRef;
    /** @var integer */
    private int $expiration;

    /** @var Clause */
    private Clause $clauses;

    /** @var integer */
    private int $gasPriceCoef;

    /** @var integer */
    private int $gas;

    /** @var string */
    private string $nonce;

    /** @var Signature|null */
    private ?Signature $signature;


    /** @var string|null */
    private ?string $dependsOn;

    /** @var Reserved */
    private Reserved $reserved;

    /**
     * @return string
     */
    public function getChainTag(): string
    {
        return $this->chainTag;
    }

    /**
     * @return string
     */
    public function getBlockRef(): string
    {
        return $this->blockRef;
    }

    /**
     * @return int
     */
    public function getExpiration(): int
    {
        return $this->expiration;
    }

    /**
     * @return Clause
     */
    public function getClauses(): Clause
    {
        return $this->clauses;
    }

    /**
     * @return int
     */
    public function getGasPriceCoef(): int
    {
        return $this->gasPriceCoef;
    }

    /**
     * @return int
     */
    public function getGas(): int
    {
        return $this->gas;
    }

    /**
     * @return string
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @return Signature|null
     */
    public function getSignature(): ?Signature
    {
        return $this->signature;
    }

    /**
     * @return string|null
     */
    public function getDependsOn(): ?string
    {
        return $this->dependsOn;
    }

    /**
     * @return Reserved
     */
    public function getReserved(): Reserved
    {
        return $this->reserved;
    }

    /**
     * @param Signature|null $signature
     */
    public function setSignature(?Signature $signature): void
    {
        $this->signature = $signature;
    }


    /**
     * Transaction constructor.
     * @param string $chainTag
     * @param string $blockRef
     * @param int $expiration
     * @param Clauses $clauses
     * @param int $gasPriceCoef
     * @param int $gas
     * @param int $nonce
     * @param string $signature
     * @param string $dependsOn
     * @param string|Reserved $reserved
     */
    public function __construct
    (
        string $chainTag,
        string $blockRef,
        int $expiration,
        Clause $clauses,
        int $gasPriceCoef,
        int $gas,
        string $nonce,
        ?string $dependsOn = null,
        $reserved
    )
    {
        $this->chainTag = $chainTag;
        $this->blockRef = $blockRef;
        $this->expiration = $expiration;
        $this->clauses = $clauses;
        $this->gasPriceCoef = $gasPriceCoef;
        $this->gas = $gas;
        $this->nonce = $nonce;
//        $this->signature = $signature;
        $this->dependsOn = $dependsOn;
        $this->reserved = $reserved;
    }


    public static function String2Hex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }

    public function serialzeTransaction($transaction)
    {

        $expiration = $transaction->expiration;

        $data = new Base16();
        $data->append($transaction->chainTag);
        $data->append($transaction->blockRef);
        $data->append(strval($expiration));
        $data->append(strval($transaction->clauses->to));
        $data->append(strval($transaction->clauses->value));
        $data->append(($transaction->clauses->data[0]));
        $data->append(strval($transaction->gasPriceCoef));
        $data->append(strval($transaction->gas));
        $data->append($transaction->nonce);
//        $data->append(strval($transaction->dependsOn));
        $data->append(strval($transaction->reserved->features));
//        $data->append($transaction->reserved->unused);

        return $data;
    }


    public static function generateTx()
    {


        /*Private Key*/
        $privateKey = "e4ad1d43183137644053aac458a6ebc20029b27c616b0a2fea6d6b10f27f36af";

        /*Post Transactions*/
        $secp = new Secp256k1();
        //Get Public Key From Private Key
        //$publicKey= $secp->getPublicKey($privateKey);
        //Derive Address From Public Key
        //$uncompressedPublicKey=$publicKey->getCompressed()->value();

        //
        //Blake Enc
        $blake2b = new \deemru\Blake2b();

        $clause = new  Clause("0x6d48628bb5bf20e5b4e591c948e0394e0d5bb078", 0, ['0x74f667c4']);

        $reserved = new Reserved(1);
        $transaction = new self(
            "0x27",
            "0x004984e1064ed410",
            30 * 8640,
            $clause,
            0,
            50000,
            '0',
            null,
            $reserved
        );
        $serialzedTransaction=self::serialzeTransaction($transaction);

        print_r($serialzedTransaction);die();


        //Rlp
        $rlp = new RLP;
        $encoded = $rlp->encode([$serialzedTransaction]);

        $encoded = "0x" . $encoded;

        print_r($encoded);
        die();


// only accept 0x prefixed hex string

        $decoded = $rlp->decode($encoded);

//        $hex2binData= hex2bin($decoded[0]);
//        print_r($hex2binData);
        echo "<br>";
        echo "unserialied transaction";
        echo "<br>";

        print_r(Str::decodeHex($decoded[0]));

//        print_r(unserialize($hex2binData));
        die();


//        echo "<pre>";
//        print_r($serialzedTransaction);
//        die();

        //Hash Transaction with blake2b 256

        $blakeHashedTransaction = $blake2b->hash($transaction);

        $blakeHashedTransaction = Transaction::String2Hex($blakeHashedTransaction);


        //Neglect 1st 24 chars
        $blakestring = substr($blakeHashedTransaction, 24);

        //Append 0x
        $blakeHexString = "0x" . $blakestring;


        //Hash Transaction with Keccak
        $hashTransaction = \VchainThor\Keccak\Keccak::hash($transaction, 256);

        //Neglect 1st 24 chars
        $string = substr($hashTransaction, 24);

        //Append 0x
        $hexString = "0x" . $string;

        //print_r($hexString);die();
        //Convert To Base16 Private Key$blake2b
        $b16PrivateKey = new \Comely\DataTypes\Buffer\Base16();
        $b16PrivateKey->set($privateKey);


        $b16Hash = new \Comely\DataTypes\Buffer\Base16();
        //Convert To Base16 Private Key
        $b16Hash->set($blakeHexString);

//
        $sign = $secp->sign($b16PrivateKey, $b16Hash);

        $transaction->setSignature($sign);

//        print_r($transactionArray);die();

        //Rlp
        $rlp = new RLP;

        //Serialzing Transaction Props
        $empty = [];
        $chainTag = ($transaction->getChainTag());
        $chainTagRlp = new RLP();
        $chainTagRlp = $chainTagRlp->encode($chainTag);

        $blockRef = ($transaction->getBlockRef());
        $blockRefRlp = new RLP();
        $blockRefRlp = $blockRefRlp->encode([$blockRef]);


        $expiration = serialize($transaction->getExpiration());
        $expirationRlp = new RLP();
        $expirationRlp = $expirationRlp->encode([$expiration]);


        $to = serialize($transaction->getClauses()->getTo());
        $toRlp = new RLP();
        $toRlp = $toRlp->encode([$to]);

        $value = serialize($transaction->getClauses()->getValue());
        $valueRlp = new RLP();
        $valueRlp = $valueRlp->encode([$value]);

        $data = serialize($transaction->getClauses()->getData());
        $dataRlp = new RLP();
        $dataRlp = $dataRlp->encode([$data]);


        $gasPriceCoef = serialize($transaction->getGasPriceCoef());
        $gasPriceCoefRlp = new RLP();
        $gasPriceCoefRlp = $gasPriceCoefRlp->encode([$data]);

        $gas = serialize($transaction->getGas());
        $gasRlp = new RLP();
        $gasRlp = $gasRlp->encode([$gas]);

        $nonce = serialize($transaction->getNonce());
        $nonceRlp = new RLP();
        $nonceRlp = $nonceRlp->encode([$nonce]);


        $sign = serialize($transaction->getSignature());
        $signRlp = new RLP();
        $signRlp = $signRlp->encode([$sign]);


        $signautreR = serialize($transaction->getSignature()->r());
        $signautreS = serialize($transaction->getSignature()->s());
        $features = serialize($transaction->getReserved()->getFeatures());
        $featuresRlp = new RLP();
        $featuresRlp = $featuresRlp->encode([$features]);

        $unused = serialize($transaction->getReserved()->getUnused());
        $unusedRlp = new RLP();
        $unusedRlp = $unusedRlp->encode([$unused]);


        $dataTx = array_push(
            $empty,
            $chainTagRlp,
            $blockRefRlp,
            $expirationRlp,
            $toRlp,
            $valueRlp,
            $dataRlp,
            $gasPriceCoefRlp,
            $gasRlp,
            $nonceRlp,
            $signautreR,
            $featuresRlp,
            $unusedRlp
        );
        //Rlp Encoding

        $tx = $rlp->encode($empty);

        die($tx);
        $decode = $rlp->decode($tx);
        print_r($decode);
        die();

//        $blakeTx= new Blake2b();
//        $blakeTx->hash($tx);
//            print_r($blakeTx);
//            die();
        print_r($tx);
        die();
    }


}