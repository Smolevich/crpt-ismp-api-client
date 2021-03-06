<?php

declare(strict_types=1);

namespace Lamoda\IsmpClient\Impl\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Lamoda\IsmpClient\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class SymfonySerializerAdapterFactory
{
    public static function create(): SerializerInterface
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory, new MetadataAwareNameConverter(
            $classMetadataFactory,
            new CamelCaseToSnakeCaseNameConverter()
        ));
        $encoder = new JsonEncoder();

        $facadeCisListDenormalizer = new FacadeCisListResponseDenormalizer();

        $symfonySerializer = new Serializer(
            [
                $facadeCisListDenormalizer,
                $normalizer,
            ],
            [$encoder]
        );

        return new SymfonySerializerAdapter($symfonySerializer);
    }
}
