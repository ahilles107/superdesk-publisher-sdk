<?php

declare(strict_types=1);

namespace AHS\Publisher;

use AHS\LoggerTrait;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractPublisher
{
    use LoggerTrait;

    /**
     * @param string $fileName
     * @param string $path
     * @param string $content
     */
    protected function saveContentToFile(string $fileName, string $path, string $content): void
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($path);
        file_put_contents($path.'/'.$fileName, $content);
    }
}
