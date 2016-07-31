<?php

/*
 * Copyright 2016 Piotr Rusin <piotr.rusin88@gmail.com>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
