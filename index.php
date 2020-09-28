<?php

use VchainThor\Vchain;
use VchainThor\Transaction\Transaction;

require_once 'vendor/autoload.php';
$serverUrl = "http://185.244.248.29";

$vchain = new Vchain("185.244.248.29", "8669");



$txResponse = new VchainThor\Transactions\TxBuilder();
//Convert Private Key To Base16
$b16PrivKey = new \Comely\DataTypes\Buffer\Base16();
$b16PrivKey->set("fad9c8855b740a0b7ed4c221dbad0f33a83a49cad6b3fe8d5817ac83d38b6a19");


$txResponse->setChainTag("0x4a");
$txResponse->setNonce(5475834264257970848);
$txResponse->setBlockRef("7023094-f244f89b");
$txResponse->setGas(21000);
$txResponse->setGasPriceCoef(128);
$txResponse->setExpiration(32);
$txResponse->setDependsOn("0x5c44dd09ae71b19a5be9975b322a54779aaf35dfbef28c9498d55c4cc66d3309");
$clauseBody = new \VchainThor\Clause\clauseBody("0x03596a5ac91e97fc7ee6e4d7088683fe4b179dfd", 25);

$clause = new \VchainThor\Clause\Clause($clauseBody);


$txResponse->setClauses([$clause]);
$reserved = new \VchainThor\Transaction\Reserved(32, []);
$txResponse->setReserved($reserved);

echo("<pre>");
echo "Without Signature Tx Serialization";
echo "<br>";
$tx = ($txResponse->serialize());
$tx = ($tx->serialized()->hexits(true));
var_dump($tx);


//Hash With Blake2b
$hashedBlake = $txResponse->blake2bHash($tx);
echo "<br>";

echo "Signature";
echo "<br>";

//Signature with SECP

//Convert Blake Message To Base16
$b16KeccakMessage = new \Comely\DataTypes\Buffer\Base16();
$b16KeccakMessage->set(bin2hex($hashedBlake));
$sign = $txResponse->signTx($b16PrivKey, $b16KeccakMessage);
var_dump($sign);
echo "<br>";

//Complete Tx
echo "Complete Tx";
echo "<br>";
$txResponse->setSignature($sign->getDER()->hexits(true));
$tx = ($txResponse->serialize(true));

var_dump($tx);

//\VchainThor\Transactions\TxBuilder::Decode($txResponse->serialize());
die();

/*END Post Transactions*/



//$data = $vchain->account->accountDetails("0x5034aa590125b64023a0262112b98d72e3c8e40e");

//$data = $vchain->account->retrieveAccountCode("0x5034aa590125b64023a0262112b98d72e3c8e40e");
//$data = $vchain->account->retrieveAccountValue("0x5034aa590125b64023a0262112b98d72e3c8e40e","0x0000000000000000000000000000000000000000000000000000000000000001");
//$data = $vchain->block->getBlock("best");

//$data = $vchain->logs->getEventLogs(0, 1000000, 0, 10, "0x0000000000000000000000000000456E65726779", ["0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef","0x0000000000000000000000005034aa590125b64023a0262112b98d72e3c8e40e"]);
//$data = $vchain->logs->getTransferLogs(0, 1000000, 0, 10, "0x0000000000000000000000000000456E65726779", "0xe59d475abe695c7f67a8a2321f33a856b0b4c71d", "0x7567d83b7b8d80addcb281a71d54fc7b3364ffed");
//$data = $vchain->node->getConnectedPeers();
//$data = $vchain->subscription->subscribeNewBlock();
//$data = $vchain->debug->createTracer(null, "0x000dabb4d6f0a80ad7ad7cd0e07a1f20b546db0730d869d5ccb0dd2a16e7595b/0/0");
//$data = $vchain->debug->debugStorageRange("0xa4627036e2095eb71c2341054daa63577c062498", "0x0000000000000000000000000000000000000000000000000000000000000000","0x000edefb448685f9c72fc2b946980ef51d8d208bbaa4d3fdcf0c57d4847aca2e/0/0");
//$data = $vchain->transactionApi->createTransaction("0x605f10b7fea015db47c21bcc4d85578a621e88b1e8442644c8765b93439a6069");
//$data = $vchain->transactionApi->getTransactionReceipt("0x605f10b7fea015db47c21bcc4d85578a621e88b1e8442644c8765b93439a6069");
//echo "<pre>";


//var_dump($data);
//die("jere");

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
//Sample Calls
//$result = $vchain->accounts($params);
$result = $vchain->networkPeers();
echo "<pre>";
var_dump($result);
die();
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
    var_dump($e);
}


echo "<pre>";
var_dump($result);
die();


//$params = array (
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
//            0 =>
//                array (
//                    'txOrigin' => '0xe59d475abe695c7f67a8a2321f33a856b0b4c71d',
//                    'sender' => '0xe59d475abe695c7f67a8a2321f33a856b0b4c71d',
//                    'recipient' => '0x7567d83b7b8d80addcb281a71d54fc7b3364ffed',
//                ),
//        ),
//    'order' => 'asc',
//);
//
//$result = $vchain->logsTransfer($params);


//$result = $vchain->peers();

//$result = $vchain->subscriptionsBlock();


//$params ='0x9bcc6526a76ae560244f698805cc001977246cb92c2b4f1e2b7a204e445409ea';
//try {
//    $result = $vchain->transactions($params);
//
//
//    echo "<pre>";
//    var_dump($result);
//}catch (Exception $e){
//    echo $e->getMessage();
//}

//try{
//    $result = $vchain->receipt("0x9bcc6526a76ae560244f698805cc001977246cb92c2b4f1e2b7a204e445409ea");
//}catch (Exception $e){
// echo $e->getMessage();exit;
//}

try {
    $params = array("raw" =>
        "0xf86981ba800adad994000000000000000000000000000000000000746f82271080018252088001c0b8414792c9439594098323900e6470742cd877ec9f9906bca05510e421f3b013ed221324e77ca10d3466b32b1800c72e12719b213f1d4c370305399dd27af962626400");
    $result = $vchain->transactions($params);

    echo '<pre>';
    var_dump($result);
} catch (Exception $e) {
    echo $e->getMessage();
}
die();

