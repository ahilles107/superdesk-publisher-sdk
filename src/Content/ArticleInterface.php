<?php

declare(strict_types=1);

namespace AHS\Content;

interface ArticleInterface
{
    public function getId(): ?int;

    public function getNumber();

    public function setNumber($number = null): void;

    public function getCreatedAt(): \DateTime;

    public function getPublishedAt(): ?\DateTime;

    public function getAuthors(): array;

    public function getKeywords(): array;

    public function getTitle(): string;

    public function getDescription(): ?string;

    public function getFields(): array;

    public function getUrl(): ?string;

    public function getImages(): array;

    public function setImages(array $images = null);

    public function getLanguage(): ?string;

    public function getIssue(): ?string;

    public function getSection(): ?string;

    public function getImage(): ?Image;

    public function getBody(): string;

    public function setBody(string $body): void;

    public function getType(): ?string;

    public function isPublished(): bool;

    public function setExtra(array $extra): void;

    public function getExtra(): array;

    public function getCategories(): array;

    public function setCategories(array $categories);
}
