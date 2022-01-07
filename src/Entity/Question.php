<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Behavior\Blamable;
use App\Behavior\Timestampable;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    use Blamable;
    use Timestampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Blocks::class, inversedBy="questions")
     */
    private $block;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=TypeQuestion::class, inversedBy="questions")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $template;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dependences;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private $validations;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private $attributes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orden;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $state;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlock(): ?Blocks
    {
        return $this->block;
    }

    public function setBlock(?Blocks $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?TypeQuestion
    {
        return $this->type;
    }

    public function setType(?TypeQuestion $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getDependences(): ?string
    {
        return $this->dependences;
    }

    public function setDependences(?string $dependences): self
    {
        $this->dependences = $dependences;

        return $this;
    }

    public function getValidations(): ?string
    {
        return $this->validations;
    }

    public function setValidations(?string $validations): self
    {
        $this->validations = $validations;

        return $this;
    }

    public function getAttributes(): ?string
    {
        return $this->attributes;
    }

    public function setAttributes(?string $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(?int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(?int $state): self
    {
        $this->state = $state;

        return $this;
    }
}
