<?php

namespace App\Tests\Service;

use App\Component\PriceCalculator;
use App\DTO\ProductDTO;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductService $productService;

    function getPaginatorMock() : PaginationInterface {
        $paginatorMock = $this->createMock(PaginationInterface::class);
        $paginatorMock->method("getItems")->willReturn([[
                "name" => "BV Lean leather ankle boots",
                "sku" => "000001",
                "category" => "boots",
                "price" => 89000
            ]]);
        $paginatorMock->method("getItemNumberPerPage")->willReturn(1);
        $paginatorMock->method("getTotalItemCount")->willReturn(1);
        $paginatorMock->method("getCurrentPageNumber")->willReturn(1);
        return $paginatorMock;
    }

    function getManagerMock() : EntityManagerInterface {

        $paginatorMock = $this->getPaginatorMock();
        $productRepositoryMock = $this->createMock(ProductRepository::class);
        $productRepositoryMock->method("findProducts")->willReturn($paginatorMock);
        $managerMock = $this->createMock(EntityManagerInterface::class);
        $managerMock->method("getRepository")->willReturn($productRepositoryMock);
        return $managerMock;
    }
    protected function setUp(): void
    {
        parent::setUp();
        $managerMock = $this->getManagerMock();
        $calculator = new PriceCalculator();
        $this->productService = new ProductService($managerMock, $calculator);
    }

    public function testGetProducts(): void
    {
        $dto = new ProductDTO();
        $data = $this->productService->getProducts($dto);

        // check products
        $this->assertIsArray($data);
        $this->assertIsArray($data["products"]);
        $this->assertEquals(1, count($data["products"]));

        // check meta info
        $this->assertIsArray($data["meta"]);
        $this->assertEquals(1, $data["meta"]["limit"]);
        $this->assertEquals(1, $data["meta"]["totalItems"]);
        $this->assertEquals(1, $data["meta"]["page"]);
        $this->assertEquals(1, $data["meta"]["totalPages"]);
    }


    public function testGetProductsWithPrices(): void
    {
        $product = [
            "name" => "BV Lean leather ankle boots",
            "sku" => "000001",
            "category" => "boots",
            "price" => 89000
        ];
        $productsWithPrices = $this->productService->getProductsWithPrices(array($product));
        $this->assertNotEmpty($productsWithPrices);
        $productWithPrice = $productsWithPrices[0];
        $this->assertIsString($productWithPrice["name"]);
        $this->assertIsString($productWithPrice["sku"]);
        $this->assertIsString($productWithPrice["category"]);
        $this->assertIsArray($productWithPrice["price"]);
    }
}
