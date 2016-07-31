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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A class representing blog articles.
 *
 * @ORM\Entity
 * @ORM\Table(name="articles")
 *
 * @author Piotr Rusin <piotr.rusin88@gmail.com>
 */
class Article extends AbstractMappedPost
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="article.title.blank")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="article.slug.blank")
     */
    private $slug;

    /**
     * An object representing the author of the article.
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articles")
     * @Assert\NotBlank(message="article.author.blank")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="article")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @ORM\JoinTable(name="articles_tags")
     */
    private $tags;

    /**
     * Create a new instance of the class.
     *
     * @param \AppBundle\Entity\User $author
     */
    public function __construct(User $author)
    {
        $author->addArticle($this);
        $this->author = $author;
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Set title of the article.
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title of the article.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug representing the article.
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug representing the article.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add a comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Article
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove a comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add a tag.
     *
     * @param \AppBundle\Entity\Tag $tag
     *
     * @return Article
     */
    public function addTag(Tag $tag)
    {
        $tag->addArticle($this);
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove a tag.
     *
     * @param \AppBundle\Entity\Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $tag->removeArticle($this);
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get author of the article.
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
