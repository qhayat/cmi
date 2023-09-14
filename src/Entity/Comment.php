<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    use Guid;
    use Timestampable;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue('CUSTOM')]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    #[Groups(['comment:collection:read'])]
    protected ?UuidInterface $id;

    #[Groups(['comment:collection:read'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[Groups(['comment:collection:read'])]
    #[ORM\ManyToOne(inversedBy: 'parent')]
    private User $author;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comments')]
    private ?self $parent = null;

    #[Groups(['comment:collection:read'])]
    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $comments;

    #[Groups(['comment:collection:read'])]
    #[ORM\Column(type: 'datetime', nullable: false)]
    protected \DateTimeInterface $createdAt;

    #[Groups(['comment:collection:read'])]
    #[ORM\Column(type: 'datetime', nullable: false)]
    protected ?\DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setParent($this);
        }

        return $this;
    }

    public function removeComment(self $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getParent() === $this) {
                $comment->setParent(null);
            }
        }

        return $this;
    }
}
