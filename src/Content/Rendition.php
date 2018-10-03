<?php

declare(strict_types=1);

namespace AHS\Content;

final class Rendition
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var string
     */
    private $link;

    /**
     * @var ArticleInterface
     */
    private $article;

    /**
     * @var array
     */
    private $details;

    public function getId()
    {
        return $this->id;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    public function getLink()
    {
        return urldecode($this->link);
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function getArticle()
    {
        return $this->article;
    }

    public function setArticle($article)
    {
        $this->article = $article;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }
}
