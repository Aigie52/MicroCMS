<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 15/03/2017
 * Time: 13:52
 */

namespace MicroCMS\Domain;


class Article
{
    /**
     * Article id
     * @var integer
     */
    private $id;

    /**
     * Article title
     * @var string
     */
    private $title;

    /**
     * Article content
     * @var string
     */
    private $content;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }
}