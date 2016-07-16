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
     * Name of unregistered author.
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $authorName;

    /**
     * Email address of unregistered author.
     *
     * @ORM\Column(type="string")
     * @Assert\Email()
     */
    private $authorEmail;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="comments")
     */
    private $article;

    /**
     * @param \AppBundle\Entity\Article $article
     */
    public function __construct(Article $article)
    {
        $this->setArticle($article);
        parent::__construct();
    }

    /**
     * Set authorName.
     *
     * @param string $authorName
     *
     * @return Comment
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get authorName.
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorEmail.
     *
     * @param string $authorEmail
     *
     * @return Comment
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Get authorEmail.
     *
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
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
     * Get article
     *
     * @return \AppBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}
