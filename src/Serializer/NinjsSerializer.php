<?php

declare(strict_types=1);

namespace AHS\Serializer;

use AHS\Ninjs\Serializer\Normalizer\IgnoreNullValuesNormalizer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use AHS\Serializer\SerializerInterface as NinjsSerializerInterface;

class NinjsSerializer implements NinjsSerializerInterface
{
    protected $serializer;

    public function serialize($data, $format, array $context = []): string
    {
        return $this->getSerializer()->serialize($data, $format, $context);
    }

    public function deserialize($data, $type, $format, array $context = [])
    {
        return $this->getSerializer()->deserialize($data, $type, $format, $context);
    }

    protected function getSerializer(): SerializerInterface
    {
        if (null !== $this->serializer) {
            return $this->serializer;
        }

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter());

        $extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);

        $this->serializer = new Serializer([
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new IgnoreNullValuesNormalizer($classMetadataFactory, $metadataAwareNameConverter, null, $extractor),
        ], [new JsonEncoder()]);

        return $this->serializer;
    }
}
