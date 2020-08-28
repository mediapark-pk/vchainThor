<?php

use VchainThor\Vchain;
use VchainThor\Transaction\Transaction;
use Web3p\RLP\RLP;
use  FurqanSiddiqui\ECDSA\Curves\Secp256k1;

require_once realpath('vendor/autoload.php');
$serverUrl = "http://185.244.248.29";

$vchain = new Vchain($serverUrl, "8669");

$txResponse=Transaction::generateTx();




print_r($txResponse);die();
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

$clause = new  \VchainThor\Transaction\Clause("0x6d48628bb5bf20e5b4e591c948e0394e0d5bb078", 0, ['0x74f667c4']);

$reserved = new \VchainThor\Transaction\Reserved(1);
$transaction = new \VchainThor\Transaction\Transaction(
    "0x27",
    "0x004984e1064ed410",
    30 * 8640,
    $clause,
    0,
    50000,
    '0xa3b6232f',
    null,
    $reserved
);


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

//Rlp
$rlp = new RLP;
//Serialzing Transaction Props
$empty=[];
$chainTag = serialize($transaction->getChainTag());
$blockRef = serialize($transaction->getBlockRef());
$expiration = serialize($transaction->getExpiration());
$to = serialize($transaction->getClauses()->getTo());
$value = serialize($transaction->getClauses()->getValue());
$data = serialize($transaction->getClauses()->getData());
$gasPriceCoef = serialize($transaction->getGasPriceCoef());
$gas = serialize($transaction->getGas());
$nonce = serialize($transaction->getNonce());
$sign =serialize($transaction->getSignature());
$signautreR = serialize($transaction->getSignature()->r());
$signautreS = serialize($transaction->getSignature()->s());
$features = serialize($transaction->getReserved()->getFeatures());
$unused = serialize($transaction->getReserved()->getUnused());

$dataTx = array_push($empty,$chainTag,$blockRef,$expiration,$to,$value,$data,$gasPriceCoef,$gas,$nonce,$sign,$features,$unused);
//Rlp Encoding

$tx = $rlp->encode($empty);
print_r($tx);die();
$postTransaction=$vchain->postTransactions([$tx]);
echo "<pre>";

print_r($postTransaction);die();
print_r($transaction);
die();
//Generating Raw Tx



$encoded = $rlp->encode($transaction);
print_r($encoded);
die();

//rawTx = '0x' + tx.encode().toString('hex');
print_r($transaction);
die();

/*END Post Transactions*/

$params = array(
    'clauses' =>
        array(
            0 =>
                array(
                    'to' => '0x5034aa590125b64023a0262112b98d72e3c8e40e',
                    'value' => '0xde0b6b3a7640000',
                    'data' => '0x5665436861696e2054686f72',
                ),
        ),
    'gas' => 50000,
    'gasPrice' => '1000000000000000',
    'caller' => '0x7567d83b7b8d80addcb281a71d54fc7b3364ffed',
    'provedWork' => '1000',
    'gasPayer' => '0xd3ae78222beadb038203be21ed5ce7c9b1bff602',
    'expiration' => 1000,
    'blockRef' => '0x00000000851caf3c',
);


//$result = $diamondRpc->getNewAddress(array('doc test'));
//$result = $vchain->accounts($params);
//$result = $vchain->networkPeers();
//$result = $vchain->accountAddressCode(["0x5034aa590125b64023a0262112b98d72e3c8e40e"]);

//$result = $vchain->accountAddressStorage(["0x5034aa590125b64023a0262112b98d72e3c8e40e","0x0000000000000000000000000000000000000000000000000000000000000001"]);

//$result = $vchain->blocks('best');
//$param = array (
//    'range' =>
//        array (
//            'unit' => 'block',
//            'from' => 0,
//            'to' => 100000,
//        ),
//    'options' =>
//        array (
//            'offset' => 0,
//            'limit' => 10,
//        ),
//    'criteriaSet' =>
//        array (
//            array (
//                'address' => '0x0000000000000000000000000000456E65726779',
//                'topic0' => '0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef',
//                'topic1' => '0x0000000000000000000000005034aa590125b64023a0262112b98d72e3c8e40e',
//            ),
//        ),
//    'order' => 'asc',
//);
//
//
//$result = $vchain->filtereventlogs($param);

try {
//    $result = $vchain->transactions(["0x9bcc6526a76ae560244f698805cc001977246cb92c2b4f1e2b7a204e445409ea"]);
    $result = $vchain->blocks("best");
} catch (Exception $e) {
    print_r($e);
}

echo "<pre>";
print_r($result);
die();

