<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductDTO
{

    #[Assert\GreaterThanOrEqual(1)]
    private ?int $page = 1;

    #[Assert\GreaterThanOrEqual(5)]
    private ?int $limit = 5;
    private ?string $category = null;
    #[Assert\GreaterThan(0)]
    private ?int $priceLessThan = null;

    static function fromRequest(Request $request) : ProductDTO {
        $dto = new ProductDTO();
        $dto->setPage($request->get("page"));
        $dto->setLimit($request->get("limit"));
        $dto->setCategory($request->get("category"));
        $dto->setPriceLessThan($request->get("priceLessThan"));
        return $dto;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     */
    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return int|null
     */
    public function getPriceLessThan(): ?int
    {
        return $this->priceLessThan;
    }

    /**
     * @param int|null $priceLessThan
     */
    public function setPriceLessThan(?int $priceLessThan): void
    {
        $this->priceLessThan = $priceLessThan;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }
}