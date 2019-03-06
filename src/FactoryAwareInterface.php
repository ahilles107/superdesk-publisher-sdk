<?php

declare(strict_types=1);

namespace AHS;

use AHS\Factory\FactoryInterface;

interface FactoryAwareInterface
{
    public function setFactory(FactoryInterface $factory): void;
}
