<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WordFilterRepository")
 */
class WordFilter
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
    private $badWord;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBadWord(): ?string
    {
        return $this->badWord;
    }

    public function setBadWord(string $badWord): self
    {
        $this->badWord = $badWord;

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validRegex(ExecutionContextInterface $context, $payload)
    {
        if (@preg_match($this->getBadWord(), null) === false) {
            $context->buildViolation('Not Valid Regex')
            ->atPath('badWord')
            ->addViolation();
        }
    }

}
