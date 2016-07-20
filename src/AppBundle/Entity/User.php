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

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A class representing registered users of the blog, which includes
 * all its admins, blog post authors, moderators and registered readers.
 * 
 * @ORM\Entity
 * @ORM\Table(name="users")
 * 
 * @author Piotr Rusin <piotr.rusin88@gmail.com>
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * Comments authored by the user
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="registeredAuthor")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Add a comment
     *
     * @param \AppBundle\Entity\Comment $comment
     * @return User
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove a comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
