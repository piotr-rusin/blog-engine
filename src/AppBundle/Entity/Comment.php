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
 * A class representing user comments associated with blog articles.
 *
 * @ORM\Entity
 * @ORM\Table(name="comments")
 *
 * @author Piotr Rusin <piotr.rusin88@gmail.com>
 */
class Comment extends AbstractMappedPost
{
    /**
     * An object representing a registered author of the comment, if they exist.
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @Assert\NotBlank(
     *      groups={"RegisteredAuthor"},
     *      message="comment.registered_author.blank"
     * )
     * @Assert\Blank(
     *      groups={"UnregisteredAuthor"},
     *      message="comment.registered_author.not_blank"
     * )
     */
    private $registeredAuthor;

    /**
     * Name of unregistered author.
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank(
     *      groups={"UnregisteredAuthor"},
     *      message="comment.unregistered_author_name.blank"
     * )
     * @Assert\Blank(
     *      groups={"RegisteredAuthor"},
     *      message="comment.unregistered_author_name.not_blank"
     * )
     */
    private $unregisteredAuthorName;

    /**
     * Email address of unregistered author.
     *
     * @ORM\Column(type="string")
     * @Assert\Email(
     *      groups={"UnregisteredAuthor"},
     *      message="comment.unregistered_author_email.invalid"
     * )
     * @Assert\Blank(
     *      groups={"RegisteredAuthor"},
     *      message="comment.unregistered_author_email.not_blank"
     * )
     */
    private $unregisteredAuthorEmail;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
     */
    private $article;

    /**
     * Create a new instance of the class.
     *
     * @param \AppBundle\Entity\Article $article
     * @param \AppBundle\Entity\User    $registeredAuthor
     * @param string                    $authorName
     * @param string                    $authorEmail
     */
    public function __construct(
        Article $article,
        User $registeredAuthor = null,
        $authorName = null,
        $authorEmail = null
    ) {
        $registeredAuthor->addComment($this);
        $this->registeredAuthor = $registeredAuthor;
        $this->setUnregisteredAuthorName($authorName);
        $this->setUnregisteredAuthorEmail($authorEmail);
        $this->setArticle($article);
        parent::__construct();
    }

    /**
     * Set name of unregistered author.
     *
     * @param string $authorName
     *
     * @return Comment
     */
    public function setUnregisteredAuthorName($authorName)
    {
        $this->unregisteredAuthorName = $authorName;

        return $this;
    }

    /**
     * Get name of unregistered author.
     *
     * @return string
     */
    public function getUnregisteredAuthorName()
    {
        return $this->unregisteredAuthorName;
    }

    /**
     * Set email address of unregistered author.
     *
     * @param string $authorEmail
     *
     * @return Comment
     */
    public function setUnregisteredAuthorEmail($authorEmail)
    {
        $this->unregisteredAuthorEmail = $authorEmail;

        return $this;
    }

    /**
     * Get email address of unregistered author.
     *
     * @return string
     */
    public function getUnregisteredAuthorEmail()
    {
        return $this->unregisteredAuthorEmail;
    }

    /**
     * Set article to which the comment belongs.
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Comment
     */
    public function setArticle(Article $article)
    {
        if ($this->article !== null) {
            $this->article->removeComment($this);
        }
        $article->addComment($this);
        $this->article = $article;

        return $this;
    }

    /**
     * Get article to which the comment belongs.
     *
     * @return \AppBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Get registered author.
     *
     * @return \AppBundle\Entity\User
     */
    public function getRegisteredAuthor()
    {
        return $this->registeredAuthor;
    }
}
