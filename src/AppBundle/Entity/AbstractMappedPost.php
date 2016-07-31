<?php

/*
 * The MIT License
 *
 * Copyright 2016 Piotr Rusin <piotr.rusin88@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A base class for entities representing postable content
 * (articles, comments, etc.).
 *
 * @ORM\MappedSuperclass
 *
 * @author Piotr Rusin <piotr.rusin88@gmail.com>
 */
abstract class AbstractMappedPost
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="post.content.blank")
     * @Assert\Length(
     *      min="5",
     *      minMessage="post.content.too_short",
     *      max="10000",
     *      maxMessage="post.content.too_long",
     *      groups={"Comment"}
     * )
     * @Assert\Length(
     *      min="10",
     *      minMessage="post.content.too_short",
     *      groups={"Article"}
     * )
     */
    protected $content;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="post.publication_date.invalid")
     */
    protected $publicationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $published;

    /**
     * Create a new instance of the class.
     */
    public function __construct()
    {
        $this->publicationDate = new \DateTime();
    }

    /**
     * Set publication date.
     *
     * @param \DateTime $publicationDate
     *
     * @return AbstractMappedPost
     */
    public function setPublicationDate(\DateTime $publicationDate)
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * Get publication date.
     *
     * @return \DateTime
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return AbstractMappedPost
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set publication status of the post.
     *
     * @param bool $published
     *
     * @return AbstractMappedPost
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Check if the post is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->published;
    }
}
