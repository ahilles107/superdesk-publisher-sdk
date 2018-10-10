<?php

declare(strict_types=1);

namespace AHS\Serializer;

use Symfony\Component\Serializer\SerializerInterface as BaseSerializerInterface;

interface SerializerInterface extends BaseSerializerInterface
{
    /**
     * @param mixed  $data
     * @param string $format
     * @param array  $context
     *
     * @return string
     */
    public function serialize($data, $format, array $context = array()): string;

    /**
     * @param mixed  $data
     * @param string $type
     * @param string $format
     * @param array  $context
     *
     * @return object
     */
    public function deserialize($data, $type, $format, array $context = array());
}
