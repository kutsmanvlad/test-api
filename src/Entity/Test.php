<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 */
class Test
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $NameTest;
		
	/**
     * @ORM\Column(type="string", nullable=true, length=50)
     */
    private $Identifier;
	
    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateCreate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameTest(): ?string
    {
        return $this->NameTest;
    }

    public function setNameTest(string $NameTest): self
    {
        $this->NameTest = $NameTest;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }
	
	public function getIdentifier(): ?string
    {
        return $this->Identifier;
    }

    public function setIdentifier(string $Identifier): self
    {
        $this->Identifier = $Identifier;

        return $this;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->DateCreate;
    }

    public function setDateCreate(\DateTimeInterface $DateCreate): self
    {
        $this->DateCreate = $DateCreate;

        return $this;
    }
}
