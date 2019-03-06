<?php

declare(strict_types=1);

namespace AHS\Content;

interface ImageInterface
{
    public function getId(): ?int;

    public function setId(int $id): void;

    public function getIdentifier();

    public function getLocation(): string;

    public function setLocation(string $location): void;

    public function getBasename(): string;

    public function setBasename(string $basename): void;

    public function getHref(): ?string;

    public function setHref(string $href): void;

    public function getThumbnailPath(): ?string;

    public function setThumbnailPath(string $thumbnailPath): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function getWidth(): ?string;

    public function setWidth(string $width): void;

    public function getHeight(): ?string;

    public function setHeight(string $height): void;

    public function getPhotographer(): ?string;

    public function setPhotographer(string $photographer): void;

    public function getPhotographerUrl(): ?string;

    public function setPhotographerUrl(string $photographerUrl): void;

    public function getPlace(): string;

    public function setPlace(string $place): void;

    public function getDomain(): string;

    public function setDomain(string $domain): void;
}
