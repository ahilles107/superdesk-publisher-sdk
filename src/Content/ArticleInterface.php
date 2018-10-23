<?php

declare(strict_types=1);

namespace AHS\Content;

interface ArticleInterface
{
    public function getId(): ?int;

    public function getIdentifier(): ?int;

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

    public function getImage();

    public function getBody(): string;

    public function setBody(string $body): void;

    public function getType(): ?string;
}
