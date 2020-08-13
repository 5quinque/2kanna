<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @Vich\Uploadable
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *     max = 500,
     *     maxMessage = "Your message cannot be longer than {{ limit }} characters"
     * )
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Board", inversedBy="post")
     * @ORM\JoinColumn(nullable=false)
     */
    private $board;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="child_post")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent_post;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="parent_post")
     * @ORM\OrderBy({"created" = "ASC"})
     */
    private $child_post;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $latestpost;

    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="imageName")
     * We're using Assert\File, instead of Assert\Image, as we accept videos
     * So we also assert with 'imageDimensionsValidate' to check the width/height of images
     * @Assert\File(
     *     payload={"severity"="error"},
     *     maxSize = "4096k",
     *     maxSizeMessage = "Exceeded file size limit of 4MB",
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/gif",
     *          "video/webm"
     *     },
     *     mimeTypesMessage = "We don't support that filetype."
     * )
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Ip
     */
    private $ipAddress;

    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $imageMimeType;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->child_post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(?Board $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getRootParentPost(): self
    {
        if (null === $this->getParentPost()) {
            return $this;
        }

        $parent = $this->getParentPost();
        while ($parent->getParentPost()) {
            $parent = $parent->getParentPost();
        }

        return $parent;
    }

    public function getParentPost(): ?self
    {
        return $this->parent_post;
    }

    public function setParentPost(?self $parent_post): self
    {
        $this->parent_post = $parent_post;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildPost(): Collection
    {
        return $this->child_post;
    }

    public function addChildPost(self $childPost): self
    {
        if (!$this->child_post->contains($childPost)) {
            $this->child_post[] = $childPost;
            $childPost->setParentPost($this);
        }

        return $this;
    }

    public function removeChildPost(self $childPost): self
    {
        if ($this->child_post->contains($childPost)) {
            $this->child_post->removeElement($childPost);
            // set the owning side to null (unless already changed)
            if ($childPost->getParentPost() === $this) {
                $childPost->setParentPost(null);
            }
        }

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getLatestpost(): ?\DateTimeInterface
    {
        return $this->latestpost;
    }

    public function setLatestpost(?\DateTimeInterface $latestpost): self
    {
        $this->latestpost = $latestpost;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    // @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
    public function setImageFile($imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    /**
     * @Assert\Callback
     *
     * @param mixed $payload
     */
    public function messageOrImageValidate(ExecutionContextInterface $context, $payload)
    {
        if (null === $this->getMessage() && null === $this->getImageFile()) {
            $context->buildViolation('Either an image or message is required')
                ->atPath('message')
                ->addViolation()
            ;
        }
        if (null !== $this->getMessage() && strlen($this->getMessage()) < 2) {
            $context->buildViolation('Your message must be at least 2 characters long')
                ->atPath('message')
                ->addViolation()
            ;
        }
    }

    /**
     * @Assert\Callback
     *
     * @param mixed $payload
     */
    public function imageDimensionsValidate(ExecutionContextInterface $context, $payload)
    {
        if (is_null($this->imageFile)) {
            return;
        }

        if (preg_match('/^image\//', $this->imageFile->getMimeType())) {
            list($imageWidth, $imageHeight) = getimagesize($this->imageFile->getPathname());
            if ($imageWidth < 1 || $imageHeight < 1) {
                $context->buildViolation('Your image is too small')
                    ->atPath('imageFile')
                    ->addViolation()
                ;
            }
            if ($imageWidth > 5000 || $imageHeight > 5000) {
                $context->buildViolation('Your image is too big')
                    ->atPath('imageFile')
                    ->addViolation()
                ;
            }
        }
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

    public function getImageMimeType(): ?string
    {
        return $this->imageMimeType;
    }

    public function setImageMimeType(?string $imageMimeType): self
    {
        $this->imageMimeType = $imageMimeType;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
