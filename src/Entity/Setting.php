<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 */
class Setting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @ORM\Column(type="integer")
     */
    private $placement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $value_bool;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity=SettingChoice::class, mappedBy="setting", orphanRemoval=true)
     * @ORM\OrderBy({"key" = "ASC"})
     */
    private $settingChoices;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    public function __construct()
    {
        $this->settingChoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getPlacement(): ?int
    {
        return $this->placement;
    }

    public function setPlacement(int $placement): self
    {
        $this->placement = $placement;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValueBool(): ?bool
    {
        return $this->value_bool;
    }

    public function setValueBool(?bool $value_bool): self
    {
        $this->value_bool = $value_bool;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(?string $section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection|SettingChoice[]
     */
    public function getSettingChoices(): Collection
    {
        return $this->settingChoices;
    }

    public function addSettingChoices(SettingChoice $settingChoices): self
    {
        if (!$this->settingChoices->contains($settingChoices)) {
            $this->settingChoices[] = $settingChoices;
            $settingChoices->setSetting($this);
        }

        return $this;
    }

    public function removeSettingChoices(SettingChoice $settingChoices): self
    {
        if ($this->settingChoices->contains($settingChoices)) {
            $this->settingChoices->removeElement($settingChoices);
            // set the owning side to null (unless already changed)
            if ($settingChoices->getSetting() === $this) {
                $settingChoices->setSetting(null);
            }
        }

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
