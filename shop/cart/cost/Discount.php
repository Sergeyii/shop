<?php

namespace shop\cart\cost;

final class Discount
{
    public $name;
    public $value;

    public function __construct(float $value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getName(): float
    {
        return $this->name;
    }
}