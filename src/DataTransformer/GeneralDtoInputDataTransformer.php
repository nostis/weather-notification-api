<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;

class GeneralDtoInputDataTransformer implements DataTransformerInterface
{
    private ResourceMetadataFactoryInterface $resourceMetadataFactory;

    public function __construct(ResourceMetadataFactoryInterface $resourceMetadataFactory)
    {
        $this->resourceMetadataFactory = $resourceMetadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($object, string $to, array $context = [])
    {
        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool {

        if (\is_object($data) || null === ($context['input']['class'] ?? null))
            return false;

        $metadata = $this->resourceMetadataFactory->create($context['resource_class'] ?? $to);

        return false === $metadata->getTypedOperationAttribute(
                $context['operation_type'],
                $context[$context['operation_type'].'_operation_name'] ?? '',
                'receive',
                true,
                false
            );
    }
}