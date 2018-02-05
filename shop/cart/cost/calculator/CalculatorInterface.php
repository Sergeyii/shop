<?php

namespace shop\cart\cost\calculator;

use shop\cart\CartItem;

interface CalculatorInterface
{
    /* @param CartItem[] $items */
    public function getCost(array $items): Cost;
}