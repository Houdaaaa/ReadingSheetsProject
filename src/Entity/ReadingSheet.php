<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReadingSheetRepository")
 */
class ReadingSheet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6, max=255)
     */
    private $Title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6, max=50)
     */
    private $Author;

    /**
     * @ORM\Column(type="integer")
     */
    private $PagesNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6, max=50)
     */
    private $Editor;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Regex(
     *     pattern="/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/",
     *     match=true
     * )
     */
    private $EditionDate;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6, max=50)
     */
    private $Collection;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6, max=50)
     */
    private $OriginalLanguage;

    /**
     * @ORM\Column(type="text")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6)
     */
    private $MainCharacters;

    /**
     * @ORM\Column(type="text")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=50)
     */
    private $Summary;

    /**
     * @ORM\Column(type="text")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=30)
     */
    private $EnjoyedExtract;

    /**
     * @ORM\Column(type="text")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false
     * )
     * @Assert\Length(min=6, max=50)
     */
    private $CriticalAnalysis;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getPagesNumber(): ?int
    {
        return $this->PagesNumber;
    }

    public function setPagesNumber(int $PagesNumber): self
    {
        $this->PagesNumber = $PagesNumber;

        return $this;
    }

    public function getEditor(): ?string
    {
        return $this->Editor;
    }

    public function setEditor(?string $Editor): self
    {
        $this->Editor = $Editor;

        return $this;
    }

    public function getEditionDate(): ?string
    {
        return $this->EditionDate;
    }

    public function setEditionDate(?string $EditionDate): self
    {
        $this->EditionDate = $EditionDate;

        return $this;
    }

    public function getCollection(): ?string
    {
        return $this->Collection;
    }

    public function setCollection(?string $Collection): self
    {
        $this->Collection = $Collection;

        return $this;
    }

    public function getOriginalLanguage(): ?string
    {
        return $this->OriginalLanguage;
    }

    public function setOriginalLanguage(?string $OriginalLanguage): self
    {
        $this->OriginalLanguage = $OriginalLanguage;

        return $this;
    }

    public function getMainCharacters(): ?string
    {
        return $this->MainCharacters;
    }

    public function setMainCharacters(string $MainCharacters): self
    {
        $this->MainCharacters = $MainCharacters;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->Summary;
    }

    public function setSummary(string $Summary): self
    {
        $this->Summary = $Summary;

        return $this;
    }

    public function getEnjoyedExtract(): ?string
    {
        return $this->EnjoyedExtract;
    }

    public function setEnjoyedExtract(string $EnjoyedExtract): self
    {
        $this->EnjoyedExtract = $EnjoyedExtract;

        return $this;
    }

    public function getCriticalAnalysis(): ?string
    {
        return $this->CriticalAnalysis;
    }

    public function setCriticalAnalysis(string $CriticalAnalysis): self
    {
        $this->CriticalAnalysis = $CriticalAnalysis;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}
