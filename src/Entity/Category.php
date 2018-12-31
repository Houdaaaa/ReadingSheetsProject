<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6, max=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6)
     */
    private $description;

    /**
     * Gets the id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Gets the description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}