<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BannedRepository")
 */
class Banned
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $ipAddress;

    /**
     * @ORM\Column(type="datetime")
     */
    private $banTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $unbanTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getBanTime(): ?\DateTimeInterface
    {
        return $this->banTime;
    }

    public function setBanTime(\DateTimeInterface $banTime): self
    {
        $this->banTime = $banTime;

        return $this;
    }

    public function getUnbanTime(): ?\DateTimeInterface
    {
        return $this->unbanTime;
    }

    public function setUnbanTime(\DateTimeInterface $unbanTime): self
    {
        $this->unbanTime = $unbanTime;

        return $this;
    }
}
