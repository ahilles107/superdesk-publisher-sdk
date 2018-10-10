<?php

declare(strict_types=1);

namespace AHS\Publisher;

use AHS\Content\ContentInterface;

interface PublisherInterface
{
    public function publish(ContentInterface $content, $printOutput = false): void;
}
