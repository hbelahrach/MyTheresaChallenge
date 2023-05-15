<?php

namespace App\Tests\DTO;

use App\DTO\ProductDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProductDTOTest extends KernelTestCase
{
    public function validateDTO(ProductDTO $dto) : ConstraintViolationListInterface {
        return self::getContainer()->get("validator")->validate($dto);
    }

    public function testValidDTO(): void
    {
        self::bootKernel();
        $dto = new ProductDTO();
        $dto->setLimit(5);
        $dto->setPage(1);
        $dto->setCategory("boots");
        $errors = $this->validateDTO($dto);
        $this->assertEmpty($errors);
    }

    public function testInvalidDTO(): void
    {
        self::bootKernel();
        $dto = new ProductDTO();
        $dto->setLimit(-1);
        $dto->setCategory("boots");
        $errors = $this->validateDTO($dto);
        $this->assertNotEmpty($errors);
    }

}
