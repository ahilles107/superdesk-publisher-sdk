<?php

declare(strict_types=1);

namespace AHS\Content;

interface ArticleInterface
{
    public function getId(): ?int;

    public function setId(int $id): void;

    public function getIdentifier(): ?int;

    public function getNumber(): ?int;

    public function setNumber(int $number = null): void;

    public function getCreatedAt(): \DateTime;

    public function setCreatedAt(\DateTime $createdAt): void;

    public function getPublishedAt(): ?\DateTime;

    public function setPublishedAt(\DateTime $publishedAt): void;

    public function getAuthors(): array;

    public function setAuthors(array $authors);

    public function getKeywords(): array;

    public function setKeywords(array $keywords): void;

    public function getTitle(): string;

    public function setTitle(string $title): void;

    public function getWebcode(): ?string;

    public function setWebcode(string $webcode = null): void;

    public function setFields(array $fields): void;

    public function getFields(): array;

    public function getUrl(): ?string;

    public function setUrl(string $url = null): void;

    public function getRenditions(): array;

    public function setRenditions(array $renditions = null): void;

    public function getRendition(string $caption): ?Rendition;

    public function getLanguage(): ?string;

    public function setLanguage(string $language = null): void;

    public function getIssue(): ?string;

    public function setIssue(string $issue = null): void;

    public function getSection(): ?string;

    public function setSection(string $section = null): void;

    public function getBody(): string;

    public function setBody(string $body): void;

    public function getType(): ?string;

    public function setType(string $type): void;

    public function getCommand(): ?string;

    public function setCommand(string $command): void;
}
