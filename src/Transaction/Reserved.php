<?php
declare(strict_types=1);

namespace VchainThor\Transaction;

class Reserved
{

    public int $features;
    public array $unused;

    /**
     * Reserved constructor.
     * @param int $features
     * @param array $unused
     */
    public function __construct(int $features, ?array $unused=[])
    {
        $this->features = $features;
        $this->unused = $unused;
    }

    /**
     * @return int
     */
    public function getFeatures(): int
    {
        return $this->features;
    }

    /**
     * @return array|null
     */
    public function getUnused(): ?array
    {
        return $this->unused;
    }


}