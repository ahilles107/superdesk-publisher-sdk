<?php

declare(strict_types=1);

namespace AHS\Factory;

use AHS\Ninjs\Schema\Associations;
use AHS\Ninjs\Schema\Renditions;
use AHS\Content\ArticleInterface;
use AHS\Ninjs\Superdesk\Author;
use AHS\Ninjs\Superdesk\Item;
use AHS\Ninjs\Superdesk\Rendition;
use AHS\Content\ContentInterface;
use AHS\Content\ImageInterface;
use Behat\Transliterator\Transliterator;
use Hoa\Mime\Mime;
use AHS\Content\Rendition as ArticleRendition;

class NinjsFactory implements FactoryInterface
{
    /**
     * @var string
     */
    protected $publicDirPath;

    public function __construct(string $publicDirPath)
    {
        $this->publicDirPath = $publicDirPath;
    }

    public function create(ArticleInterface $article): Item
    {
        $item = $this->createArticle($article);

        if (\count($article->getRenditions()) > 0) {
            $featureMedia = $this->createMedia($article->getRenditions()[0]);
            if (null !== $featureMedia) {
                $associations = new Associations();
                $associations->add('featuremedia', $this->createMedia($article));
                $item->setAssociations($associations);
            }
        }

        return $item;
    }

    public function createArticle(ArticleInterface $article): Item
    {
        $item = new Item((string) $article->getUrl());
        $item->setDescriptionHtml($this->getDescription($article));
        $item->setDescriptionText(strip_tags($this->getDescription($article)));
        $item->setBodyHtml($article->getBody());
        $item->setBodyText(strip_tags($article->getBody()));
        $item->setVersion('1');
        $item->setHeadline($article->getTitle());
        $item->setSlugline(Transliterator::urlize($article->getTitle()));
        $item->setVersioncreated($article->getPublishedAt());
        $item->setUrgency(5);
        $item->setPriority(5);
        $item->setPubstatus('usable');
        $item->setLanguage('pt');

        $this->setAuthor($article, $item);
        $this->setCategory($article, $item);
        $this->setExtra($article, $item);
        $this->removeStylingFromBody($article);

        return $item;
    }

    public function createImageItem(ImageInterface $image)
    {
        $imageItem = new Item($image->getDomain().'/images/'.$image->getBasename());
        $extension = pathinfo($imageItem->getGuid(), PATHINFO_EXTENSION);
        $mimeType = Mime::getMimeFromExtension($extension);
        if (null === $mimeType || null === $image->getWidth() || null === $image->getHeight()) {
            return;
        }
        $imageItem->setType('picture');
        $imageItem->setHeadline($image->getDescription() ? strip_tags($image->getDescription()) : 'Image #'.$image->getId());
        $imageItem->setDescriptionHtml($image->getDescription());
        $imageItem->setDescriptionText(strip_tags($image->getDescription()));
        $imageItem->setVersion('1');
        if ($image->getPhotographer()) {
            $author = new Author();
            $author->setName($image->getPhotographer());
            $author->setRole('photographer');
            $imageItem->setByline($author->getName());
            $imageItem->addAuthor($author);
        }
        $imageItem->setUrgency(5);
        $imageItem->setPriority(5);
        $imageItem->setLanguage('en');
        $imageItem->setUsageterms('indefinite-usage');
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

    public function getRenditionNames(): array
    {
        return ['article_small_image'];
    }

    public function getDescription(ArticleInterface $article): string
    {
        if (array_key_exists('deck', $article->getFields())) {
            return $article->getFields()['deck'];
        }

        return '';
    }

    public function setCategory(ArticleInterface $article, Item $item): void
    {
        // not implemented by default
        return;
    }

    public function setExtra(ArticleInterface $article, Item $item): void
    {
        // not implemented by default
        return;
    }

    public function isSupported(ContentInterface $content): bool
    {
        return true;
    }

    protected function createMedia(ArticleRendition $rendition): ?Item
    {
        $imagePath = $this->publicDirPath.$rendition->getLink();
        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
        $imageFileName = pathinfo($imagePath, PATHINFO_BASENAME);
        list($width, $height) = getimagesize($imagePath);
        $externalUrl = $rendition->getDetails()['original']['external_src'];

        $imageItem = new Item($externalUrl);
        $imageItem->setType('picture');
        $imageItem->setHeadline($article->getTitle());
        $caption = $rendition->getDetails()['caption'];
        if ('' === $caption) {
            $caption = $this->getDescription($article);
        }
        $imageItem->setDescriptionHtml($caption);
        $imageItem->setDescriptionText(strip_tags($caption));
        $imageItem->setVersion('1');
        $this->setAuthor($article, $imageItem);
        $imageItem->setUrgency(5);
        $imageItem->setPriority(5);
        $imageItem->setLanguage($article->getLanguage());
        $imageItem->setUsageterms('indefinite-usage');
        $imageItem->setPubstatus('usable');
        $imageItem->setVersioncreated($article->getPublishedAt());

        $renditions = new Renditions();
        $originalRendition = new Rendition($externalUrl);
        $originalRendition->setMimetype(Mime::getMimeFromExtension($extension) ? Mime::getMimeFromExtension($extension) : 'image/jpeg');
        $originalRendition->setWidth($width);
        $originalRendition->setHeight($height);
        $originalRendition->setMedia($imageFileName);
        $renditions->add('original', $originalRendition);
        $renditions->add('baseImage', $originalRendition);

        $imageItem->setRenditions($renditions);

        return $imageItem;
    }

    protected function removeStylingFromBody(ArticleInterface $article)
    {
        $output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $article->getBody());
        $article->setBody($output);
    }

    /**
     * @param ArticleInterface      $article
     * @param Item                  $item
     * @param ArticleRendition|null $rendition
     */
    protected function setAuthor(ArticleInterface $article, Item $item, ArticleRendition $rendition = null)
    {
        if (null !== $rendition) {
            $author = new Author();
            $author->setName($rendition->getDetails()['photographer']);
            $author->setRole('photographer');
            $item->setByline($author->getName());
            $item->addAuthor($author);
        }

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
