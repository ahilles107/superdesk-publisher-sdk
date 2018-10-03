<?php

declare(strict_types=1);

namespace AHS\Content;

interface ContentInterface
{
    public function getIdentifier();

    public function getOutputFileLocation(): string;

    public function getOutputFileName(): string;
}
