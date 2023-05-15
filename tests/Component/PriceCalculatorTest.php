<?php

namespace App\Tests\Component;

use App\Component\PriceCalculator;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    private PriceCalculator $priceCalculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->priceCalculator = new PriceCalculator();
    }

    public function testGetPriceInformations() : void
    {
        $priceInfo = $this->priceCalculator->getPriceInformations(89000, "boots", "000001");
        $this->assertIsArray($priceInfo);
        $this->assertEquals(89000, $priceInfo["original"]);
        $this->assertEquals(62300, $priceInfo["final"]);
        $this->assertEquals("30%", $priceInfo["discount_percentage"]);
        $this->assertEquals("EUR", $priceInfo["currency"]);
    }

    public function testGetDiscount() : void {
        $discount = $this->priceCalculator->getDiscount("hats", "000005");
        $this->assertNull($discount);

        $discount = $this->priceCalculator->getDiscount("boots", "000005");
        $this->assertEquals(30, $discount);

        $discount = $this->priceCalculator->getDiscount("hats", "000003");
        $this->assertEquals(15, $discount);

        $discount = $this->priceCalculator->getDiscount("boots", "000003");
        $this->assertEquals(30, $discount);
    }
}
