<?php

declare(strict_types=1);

namespace AHS;

use Psr\Log\LoggerInterface;

interface LoggerAwareInterface
{
    public function setLogger(LoggerInterface $logger);
}
