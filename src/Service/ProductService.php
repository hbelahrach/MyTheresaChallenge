<?php

namespace App\Service;

use App\Component\PriceCalculator;
use App\DTO\ProductDTO;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class ProductService
{
    private EntityManagerInterface $manager;
    private PriceCalculator $priceCalculator;

    public function __construct(EntityManagerInterface $manager, PriceCalculator $priceCalculator)
    {
        $this->manager = $manager;
        $this->priceCalculator = $priceCalculator;
    }

    public function getProducts(ProductDTO $dto) : array
    {
        /* @var PaginationInterface $paginator */
        $paginator = $this->manager->getRepository(Product::class)->findProducts($dto->getCategory(), $dto->getPriceLessThan(), $dto->getPage(), $dto->getLimit());
        return [
            'products' => $this->getProductsWithPrices($paginator->getItems()),
            'meta' => [
                'limit' => $paginator->getItemNumberPerPage(),
                'totalItems' => $paginator->getTotalItemCount(),
                'page' => $paginator->getCurrentPageNumber(),
                'totalPages' => ceil($paginator->getTotalItemCount() / $paginator->getItemNumberPerPage()),
            ],
        ];
    }

    public function getProductsWithPrices(array $products) : array {
        $productsWithPrices = [];
        foreach ($products as $product) {
            $priceInformations = $this->priceCalculator->getPriceInformations($product["price"], $product["category"], $product["sku"]);
            $productsWithPrices[] = array_merge($product, ['price' => $priceInformations]);
        }
        return $productsWithPrices;
    }
}