<?php


namespace VchainThor\Clause;


use VchainThor\Exception\IncompleteTxException;

class clauseBody
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
    public function __construct(string $to, int $value, array $data = [])
    {
        $this->to = $to;
        $this->value = $value;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


}