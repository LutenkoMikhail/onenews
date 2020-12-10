<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",unique=true)
     */
    private $name;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\News", mappedBy="tags")
     */
    private $news;

    /**
     * @return ArrayCollection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param $news
     * @return Tag
     */
    public function setNews($news)
    {
        $this->news = $news;
        return $this;
    }

    /**
     * @param News $news
     */
    public function addNews(News $news)
    {
        $this->news[] = $news;
    }

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRound()
    {

    }

}
