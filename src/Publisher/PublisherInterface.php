<?php

declare(strict_types=1);

namespace AHS\Publisher;

use AHS\Content\ContentInterface;
use AHS\FactoryAwareInterface;
use AHS\LoggerAwareInterface;

interface PublisherInterface extends LoggerAwareInterface, FactoryAwareInterface
{
    public function publish(ContentInterface $content, $printOutput = false): void;
}
