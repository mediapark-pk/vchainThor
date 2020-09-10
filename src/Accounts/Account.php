<?php


namespace VchainThor\Accounts;


use VchainThor\Vchain;

/**
 * Class Account
 * @package VchainThor\Addresses
 */
class Account
{

    /*** @var Vchain */
    public Vchain $vchain;

    /**
     * Account constructor.
     */
    public function __construct(Vchain $vchain)
    {
        $this->vchain = $vchain;
    }


}