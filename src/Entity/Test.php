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
