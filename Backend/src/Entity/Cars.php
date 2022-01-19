<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\EntityListener\CarsEntityListener;
use App\Repository\CarsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\EntityListeners({CarsEntityListener::class})
 * @ApiResource(
 *     denormalizationContext={"groups"={"cars:post"}},
 *     normalizationContext={"groups"={"cars:get"}},
 *     itemOperations={"get", "put", "delete"}
 * )
 * @ORM\Entity(repositoryClass=CarsRepository::class)
 */
class Cars
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cars:post", "cars:get"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cars:post", "cars:get"})
     */
    private string $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cars:post", "cars:get"})
     */
    private string $mark;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cars:post", "cars:get"})
     */
    private string $about;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="car", orphanRemoval=true, cascade={"PERSIST"})
     * @var ArrayCollection<int, Comment> | PersistentCollection<int, Comment>
     * @Groups({"cars:get"})
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cars:post", "cars:get"})
     */
    private string $image;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function setMark(string $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(string $about): self
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCar($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCar() === $this) {
                $comment->setCar(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setCommentEmpty(): self
    {
        $this->comments = new ArrayCollection();

        return $this;
    }

}
