<?php

use VchainThor\Vchain;

require_once realpath('vendor/autoload.php');
$serverUrl = "http://185.244.248.29";
$testUrl = "http://seed.evonet.networks.dash.org";
$vchain = new Vchain($serverUrl, "8669");

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
//$result = $vchain->accountAddressCode();
$result = $vchain->accountAddressStorage([12]);
echo "<pre>";
print_r($result);
die("here");