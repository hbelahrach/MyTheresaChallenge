<?php

namespace App\Controller;

use App\DTO\ProductDTO;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/api')]
class ProductController extends AbstractController
{
    private ProductService $productService;
    private TranslatorInterface $translator;
    private ValidatorInterface $validator;

    public function __construct(ProductService $productService, TranslatorInterface $translator, ValidatorInterface $validator)
    {
        $this->productService = $productService;
        $this->translator = $translator;
        $this->validator = $validator;
    }

    #[Route('/products', name: 'get_products', methods: ["GET"])]
    #[OA\Parameter(
        name: 'category',
        description: 'The field is used to filter by category',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'priceLessThan',
        description: 'The field is used to filter products with price less than the provided value',
        in: 'query',
        schema: new OA\Schema(type: 'number')
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'The field is used to for pagination',
        in: 'query',
        schema: new OA\Schema(type: 'number')
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'The field is used to for pagination',
        in: 'query',
        schema: new OA\Schema(type: 'number')
    )]
    #[OA\Tag(name: 'Product')]
    public function getProducts(ProductDTO $dto): JsonResponse
    {
        $errors = $this->validator->validate($dto);
        if(count($errors) > 0) {
            $violation = $errors->get(0);
            return $this->json([
                'message' => ($violation->getPropertyPath()).' : '.$violation->getMessage(),
                'body'  => null
            ]);
        }

        $products = $this->productService->getProducts($dto);
        return $this->json([
            'message' => $this->translator->trans("product.list"),
            'body'  => $products
        ]);
    }
}