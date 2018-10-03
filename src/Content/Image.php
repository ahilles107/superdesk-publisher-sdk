<?php

declare(strict_types=1);

namespace AHS\Content;

class Image extends Content implements ImageInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $basename;

    /**
     * @var string
     */
    protected $thumbnailPath;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $width;

    /**
     * @var string
     */
    protected $height;

    /**
     * @var string
     */
    protected $photographer;

    /**
     * @var string
     */
    protected $photographerUrl;

    /**
     * @var string
     */
    protected $place;

    /**
     * @var string
     */
    private $domain;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdentifier()
    {
        return $this->getId();
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getBasename(): string
    {
        return $this->basename;
    }

    public function setBasename(string $basename): void
    {
        $this->basename = $basename;
    }

    public function getThumbnailPath(): ?string
    {
        return $this->thumbnailPath;
    }

    public function setThumbnailPath(string $thumbnailPath): void
    {
        $this->thumbnailPath = $thumbnailPath;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(string $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): void
    {
        $this->height = $height;
    }

    public function getPhotographer(): ?string
    {
        return $this->photographer;
    }

    public function setPhotographer(string $photographer): void
    {
        $this->photographer = $photographer;
    }

    public function getPhotographerUrl(): ?string
    {
        return $this->photographerUrl;
    }

    public function setPhotographerUrl(string $photographerUrl): void
    {
        $this->photographerUrl = $photographerUrl;
    }

    public function getPlace(): string
    {
        return $this->place;
    }

    public function setPlace(string $place): void
    {
        $this->place = $place;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function getOutputFileLocation(): string
    {
        return str_replace('https://', '', $this->domain).'/images/';
    }

    public function getOutputFileName(): string
    {
        return $this->getId().'.json';
    }
}
