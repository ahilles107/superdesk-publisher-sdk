<?php

declare(strict_types=1);

namespace AHS\Publisher;

use AHS\Content\ArticleInterface;
use AHS\Content\ContentInterface;
use AHS\Content\ImageInterface;
use AHS\Factory\FactoryInterface;
use AHS\Serializer\SerializerInterface;
use Psr\Log\LogLevel;
use SWP\Component\Bridge\Validator\NinjsValidator;

class NinjsPublisher extends AbstractPublisher implements PublisherInterface
{
    /**
     * @var string
     */
    protected $projectDir;

    /**
     * @var FactoryInterface
     */
    protected $ninjsFactory;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * NinjsJsonPublisher constructor.
     *
     * @param string              $projectDir
     * @param FactoryInterface    $ninjsFactory
     * @param SerializerInterface $serializer
     */
    public function __construct(string $projectDir, FactoryInterface $ninjsFactory, SerializerInterface $serializer)
    {
        $this->projectDir = $projectDir;
        $this->ninjsFactory = $ninjsFactory;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(ContentInterface $content, $printRenderedTemplate = false): void
    {
        if (!$this->ninjsFactory->isSupported($content)) {
            return;
        }

        $this->log(LogLevel::INFO, 'Creating NINJS for article with number: '.$content->getIdentifier());

        if ($content instanceof ArticleInterface) {
            $item = $this->ninjsFactory->create($content);
        } elseif ($content instanceof ImageInterface) {
            $item = $this->ninjsFactory->createImageItem($content);
        } else {
            return;
        }

        $ninJs = $this->serializer->serialize($item, 'json', ['json_encode_options' => \JSON_PRETTY_PRINT]);
        $validator = new NinjsValidator($this->logger);
        if (!$validator->isValid($ninJs)) {
            throw new \Exception('Generated ninjs is not valid');
        }

        $this->log(LogLevel::INFO, 'Generated ninjs is valid');

        if ($printRenderedTemplate) {
            $this->log(LogLevel::INFO, $ninJs);
        }

        $fileLocation = $content->getOutputFileLocation();
        $fileName = date('hi').'__'.$content->getOutputFileName();
        $path = $this->projectDir.'/public/ninjs/'.$fileLocation;

        $this->saveContentToFile($fileName, $path, $ninJs);
    }

    /**
     * @param FactoryInterface $factory
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->ninjsFactory = $factory;
    }
}
