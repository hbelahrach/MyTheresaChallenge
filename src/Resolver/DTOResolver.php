<?php

namespace App\Resolver;

use App\DTO\ProductDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Runtime\ResolverInterface;

class DTOResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        // get the argument type (ProductDTO)
        $argumentType = $argument->getType();

        if ($argumentType != ProductDTO::class)
        {
            return [];
        }
        return [ProductDTO::fromRequest($request)];
    }
}