<?php

declare(strict_types=1);

namespace AHS\Publisher;

use AHS\Factory\FactoryInterface;
use AHS\LoggerTrait;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractPublisher
{
    use LoggerTrait;

    /**
     * @var FactoryInterface
     */
    protected $ninjsFactory;

    protected function saveContentToFile(string $fileName, string $path, string $content): void
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir($path);
        file_put_contents($path.'/'.$fileName, $content);
    }

    public function setFactory(FactoryInterface $factory): void
    {
        $this->ninjsFactory = $factory;
    }
}
