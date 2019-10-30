<?php

declare(strict_types=1);

namespace AHS\Factory;

use AHS\Content\Image;
use AHS\Ninjs\Schema\Associations;
use AHS\Ninjs\Schema\Renditions;
use AHS\Content\ArticleInterface;
use AHS\Ninjs\Superdesk\Extra;
use AHS\Ninjs\Superdesk\Author;
use AHS\Ninjs\Superdesk\Item;
use AHS\Ninjs\Superdesk\Rendition;
use AHS\Content\ContentInterface;
use AHS\Content\ImageInterface;
use AHS\Ninjs\Superdesk\Service;
use Behat\Transliterator\Transliterator;
use Hoa\Mime\Mime;
use UnexpectedValueException;

class NinjsFactory implements FactoryInterface
{
    public function create(ArticleInterface $article): Item
    {
        $item = $this->createArticle($article);

        $associations = new Associations();
        foreach ($article->getImages() as $key => $image) {
            $imageItem = $this->createImageItem($image);
            $associations->add($key, $imageItem);
        }

        if ($article->getImage() instanceof Image) {
            $associations->add('featuremedia', $this->createImageItem($article->getImage()));
        }

        $item->setAssociations($associations);

        return $item;
    }

    public function createArticle(ArticleInterface $article): Item
    {
        $this->processBody($article);

        $item = new Item($article->getUrl());
        $item->setDescriptionHtml($article->getDescription($article));
        $item->setDescriptionText(strip_tags($article->getDescription($article)));
        $item->setBodyHtml($article->getBody());
        $item->setBodyText(strip_tags($article->getBody()));
        $item->setVersion('1');
        $item->setHeadline($article->getTitle());
        $item->setSlugline(Transliterator::urlize($article->getTitle()));
        $item->setVersioncreated($article->getPublishedAt());
        $item->setKeywords($article->getKeywords());
        $item->setUrgency(5);
        $item->setPriority(5);
        $item->setPubstatus('usable');
        $item->setLanguage($article->getLanguage());

        $this->setAuthor($article, $item);
        $this->setCategory($article, $item);
        $this->setExtra($article, $item);

        return $item;
    }

    public function createImageItem(ImageInterface $image): Item
    {
        $imageItem = new Item(
            $image->getHref() ?? $image->getDomain().'/'.$image->getLocation().'/'.$image->getBasename()
        );
        $extension = pathinfo($imageItem->getGuid(), PATHINFO_EXTENSION);
        $mimeType = Mime::getMimeFromExtension($extension);
        if (null === $mimeType || null === $image->getWidth() || null === $image->getHeight()) {
            throw new UnexpectedValueException('Can\'t read image data (mime, or dimensions)');
        }

        $imageItem->setType('picture');
        $imageItem->setHeadline($image->getDescription() ? strip_tags($image->getDescription()) : 'Image #'.$image->getId());
        $imageItem->setDescriptionHtml($image->getDescription());
        $imageItem->setDescriptionText(strip_tags($image->getDescription()));
        $imageItem->setBodyHtml($image->getDescription());
        $imageItem->setBodyText(strip_tags($image->getDescription()));

        $imageItem->setVersion('1');
        if ($image->getPhotographer()) {
            $author = new Author();
            $author->setName($image->getPhotographer());
            $author->setRole('photographer');
            $imageItem->setByline($author->getName());
            $imageItem->addAuthor($author);
        }
        $imageItem->setPubstatus('usable');
        $imageItem->setMimeType($mimeType);
        $imageItem->setVersioncreated(new \DateTime());

        $renditions = new Renditions();
        $originalRendition = new Rendition($imageItem->getGuid());
        $originalRendition->setMimetype($mimeType);
        $originalRendition->setWidth((int) $image->getWidth());
        $originalRendition->setHeight((int) $image->getHeight());
        $originalRendition->setMedia($image->getBasename());
        $renditions->add('original', $originalRendition);
        $renditions->add('baseImage', $originalRendition);
        $imageItem->setRenditions($renditions);

        return $imageItem;
    }

    public function setCategory(ArticleInterface $article, Item $item): void
    {
        foreach ($article->getCategories() as $code => $category) {
            $item->addService(new Service($category, $code));
        }
    }

    public function setExtra(ArticleInterface $article, Item $item, $extra = null): void
    {
        if (null === $extra) {
            $extra = new Extra();
        }

        foreach ($article->getExtra() as $key => $value) {
            $extra->add($key, $value);
        }

        $extra->add('original_published_at', $item->getVersioncreated());
        $item->setExtra($extra);
    }

    public function isSupported(ContentInterface $content): bool
    {
        return true;
    }

    protected function processBody(ArticleInterface $article): void
    {
        $output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $article->getBody());
        $output = preg_replace('#</?font[^>]*>#is', '', $output);

        $article->setBody($output);
    }

    protected function setAuthor(ArticleInterface $article, Item $item): void
    {
        $articleAuthors = $article->getAuthors();
        if (null === $articleAuthors) {
            $item->setByline('editoria');

            return;
        }
        $byline = [];
        foreach ($articleAuthors as $articleAuthor) {
            $author = new Author();
            $author->setName($articleAuthor['name']);
            if (isset($articleAuthor['biography'])) {
                $author->setBiography($articleAuthor['biography']);
            }
            if (isset($articleAuthor['image'])) {
                $author->setAvatarUrl($articleAuthor['image']);
            }

            $author->setRole('editor');
            $byline[] = $articleAuthor['name'];
            $item->addAuthor($author);
        }

        $item->setByline(implode(', ', $byline));
    }
}
