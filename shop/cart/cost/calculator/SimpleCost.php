<?php

namespace shop\cart\cost\calculator;

use shop\cart\CartItem;
use shop\cart\cost\Cost;

class SimpleCost implements CalculatorInterface
{
    /* @param CartItem[] $items */
    public function getCost(array $items): Cost
    {
        $cost = 0;

        foreach($items as $item){
            $cost += $item->getCost();
        }

        return new Cost($cost);
    }
}