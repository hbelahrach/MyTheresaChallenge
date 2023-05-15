<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Repository\ProductRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        $content = json_decode(file_get_contents('assets/products.json'), true);
        $entries = $content["products"];
        foreach ($entries as $entry) {
            $category = $this->manager->getRepository(ProductCategory::class)->findOneBy(["name" => $entry["category"]]);
            if(!$category) {
                $category = new ProductCategory();
                $category->setName($entry["category"]);
                $this->manager->persist($category);
            }

            $product = new Product();
            $product->setName($entry["name"]);
            $product->setSku($entry["sku"]);
            $product->setPrice($entry["price"]);
            $product->setCategory($category);
            $this->manager->persist($product);
            $manager->flush();
        }
    }
}
