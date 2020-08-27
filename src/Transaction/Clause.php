<?php
declare(strict_types=1);

namespace VchainThor\Transaction;


class Clause
{
    public string $to;
    public int $value;
    public array $data;

    /**
     * Clauses constructor.
     * @param string $to
     * @param int $value
     * @param array $data
     */
    public function __construct(string $to, int $value, array $data)
    {
        $this->to = $to;
        $this->value = $value;
        $this->data = $data;
    }
}