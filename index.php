<?php

use VchainThor\Vchain;

require_once 'vendor/autoload.php';
$serverUrl = "http://185.244.248.29";

$vchain = new Vchain($serverUrl, "8669");

//$params = array(
//    'clauses' =>
//        array(
//            0 =>
//                array(
//                    'to' => '0x5034aa590125b64023a0262112b98d72e3c8e40e',
//                    'value' => '0xde0b6b3a7640000',
//                    'data' => '0x5665436861696e2054686f72',
//                ),
//        ),
//    'gas' => 50000,
//    'gasPrice' => '1000000000000000',
//    'caller' => '0x7567d83b7b8d80addcb281a71d54fc7b3364ffed',
//    'provedWork' => '1000',
//    'gasPayer' => '0xd3ae78222beadb038203be21ed5ce7c9b1bff602',
//    'expiration' => 1000,
//    'blockRef' => '0x00000000851caf3c',
//);


//$result = $diamondRpc->getNewAddress(array('doc test'));
//$result = $vchain->accounts($params);
//$result = $vchain->networkPeers();
//$result = $vchain->accountAddressCode();

//$result = $vchain->accountAddressStorage([12]);

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
//    print_r($result);
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
    print_r($result);
}catch (Exception $e){
    echo $e->getMessage();
}
die();