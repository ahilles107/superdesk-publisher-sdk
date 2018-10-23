<?php

declare(strict_types=1);

namespace AHS\Serializer;

use AHS\Ninjs\Serializer\Normalizer\IgnoreNullValuesNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use AHS\Serializer\SerializerInterface as NinjsSerializerInterface;

class NinjsSerializer implements NinjsSerializerInterface
{
    public function serialize($data, $format, array $context = []): string
    {
        $context['json_encode_options'] = $context['json_encode_options'] | \JSON_UNESCAPED_UNICODE;

        return $this->getSerializer()->serialize($data, $format, $context);
    }

    public function deserialize($data, $type, $format, array $context = [])
    {
        return $this->getSerializer()->deserialize($data, $type, $format, $context);
    }

    protected function getSerializer(): SerializerInterface
    {
        $normalizer = new IgnoreNullValuesNormalizer(null, new CamelCaseToSnakeCaseNameConverter());
        $normalizer->setIgnoredAttributes(['items']);

        return new Serializer([$normalizer], [new JsonEncoder()]);
    }
}
