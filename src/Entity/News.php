<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
//use  Doctrine\ORM\Mapping\JoinTable as JoinTable;
//use  Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="text",name="short_description")
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt ;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt ;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $active;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
     * @ORM\JoinTable(name="news_tags",
     *      joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    /**
     * @return Object
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Object $tags
     * @return News
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return News
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param string  $shortDescription
     * @return News
     */
    public function setShortDescription( $shortDescription)
    {
        $this->shortDescription= $shortDescription;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return News
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return News
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return News
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return News
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
