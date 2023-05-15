<?php

namespace App\Component;

class PriceCalculator
{
    function getPriceInformations(int $price, ?string $categpry, ?string $sku)
    {
        $discount = $this->getDiscount($categpry, $sku);
        return [
            'original' => $price,
            'final' => ceil($price * (1 - $discount / 100)),
            'discount_percentage' => $discount ? $discount . "%" : $discount,
            "currency" => "EUR"
        ];
    }

    function getDiscount(?string $categpry, ?string $sku) : ?int {
        $discount = null;
        if($sku == "000003") {
            $discount = 15;
        }

        if($categpry == "boots") {
            $discount = 30;
        }
        return $discount;
    }
}