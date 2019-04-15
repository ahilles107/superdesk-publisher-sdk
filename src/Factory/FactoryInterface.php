<?php

declare(strict_types=1);

namespace AHS\Factory;

use AHS\Content\ArticleInterface;
use AHS\Content\ImageInterface;
use AHS\Ninjs\Superdesk\Item;
use AHS\Content\ContentInterface;

interface FactoryInterface
{
    public function create(ArticleInterface $article): Item;

    public function createArticle(ArticleInterface $article): Item;

    public function createImageItem(ImageInterface $image);

    public function setCategory(ArticleInterface $article, Item $item): void;

    public function setExtra(ArticleInterface $article, Item $item): void;

    public function isSupported(ContentInterface $content): bool;
}
