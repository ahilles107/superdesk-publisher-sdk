<?php

declare(strict_types=1);

namespace AHS\Serializer;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter as SymfonyCamelCaseToSnakeCaseNameConverter;

class CamelCaseToSnakeCaseNameConverter extends SymfonyCamelCaseToSnakeCaseNameConverter
{
    public function normalize($propertyName)
    {
        // FIX for default Superdesk rendition
        if ('baseImage' === $propertyName) {
            return $propertyName;
        }

        return parent::normalize($propertyName);
    }
}
