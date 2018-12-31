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

    /**
     * Gets the id
     * 
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
        return $this->Title;
    }

    /**
     * Sets the title
     */
    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    /**
     * Gets the author
     */
    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    /**
     * Sets the author
     * 
     */
    public function setAuthor(string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    /**
     * Gets the pages number
     */
    public function getPagesNumber(): ?int
    {
        return $this->PagesNumber;
    }

    /**
     * Sets the pages number
     */
    public function setPagesNumber(int $PagesNumber): self
    {
        $this->PagesNumber = $PagesNumber;

        return $this;
    }

    /**
     * Gets the editor
     */
    public function getEditor(): ?string
    {
        return $this->Editor;
    }

    /**
     * Sets the editor
     */
    public function setEditor(?string $Editor): self
    {
        $this->Editor = $Editor;

        return $this;
    }

    /**
     * Gets the edition date
     */
    public function getEditionDate(): ?string
    {
        return $this->EditionDate;
    }

    /**
     * Sets the edition date
     */
    public function setEditionDate(?string $EditionDate): self
    {
        $this->EditionDate = $EditionDate;

        return $this;
    }
 
    /**
     * Gets the collection
     */
    public function getCollection(): ?string
    {
        return $this->Collection;
    }

    /**
     * Sets the collection
     */
    public function setCollection(?string $Collection): self
    {
        $this->Collection = $Collection;

        return $this;
    }

    /**
     * Gets the original language
     */
    public function getOriginalLanguage(): ?string
    {
        return $this->OriginalLanguage;
    }

    /**
     * Sets the original language
     */
    public function setOriginalLanguage(?string $OriginalLanguage): self
    {
        $this->OriginalLanguage = $OriginalLanguage;

        return $this;
    }

    /**
     * Gets the main characters
     */
    public function getMainCharacters(): ?string
    {
        return $this->MainCharacters;
    }

    /**
     * Sets the main characters
     */
    public function setMainCharacters(string $MainCharacters): self
    {
        $this->MainCharacters = $MainCharacters;

        return $this;
    }

    /**
     * Gets the summary
     */
    public function getSummary(): ?string
    {
        return $this->Summary;
    }

    /**
     * Sets the summary
     */
    public function setSummary(string $Summary): self
    {
        $this->Summary = $Summary;

        return $this;
    }

    /**
     * Gets the enjoyed extract
     */
    public function getEnjoyedExtract(): ?string
    {
        return $this->EnjoyedExtract;
    }

    /**
     * Sets the enjoyed extract
     */
    public function setEnjoyedExtract(string $EnjoyedExtract): self
    {
        $this->EnjoyedExtract = $EnjoyedExtract;

        return $this;
    }

    /**
     * Gets the critical analysis
     */
    public function getCriticalAnalysis(): ?string
    {
        return $this->CriticalAnalysis;
    }

    /**
     * Sets the critical analysis
     */
    public function setCriticalAnalysis(string $CriticalAnalysis): self
    {
        $this->CriticalAnalysis = $CriticalAnalysis;

        return $this;
    }

    /**
     * Gets the category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Sets the category
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}
