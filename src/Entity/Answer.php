<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
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
    private $Description;

    /**
     * @ORM\Column(type="integer")
     */
    private $IdQuestion;

    /**
     * @ORM\Column(type="integer")
     */
    private $IdTest;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdQuestion(): ?int
    {
        return $this->IdQuestion;
    }

    public function setIdQuestion(int $IdQuestion): self
    {
        $this->IdQuestion = $IdQuestion;

        return $this;
    }

    public function getIdTest(): ?int
    {
        return $this->IdTest;
    }

    public function setIdTest(int $IdTest): self
    {
        $this->IdTest = $IdTest;

        return $this;
    }
}
