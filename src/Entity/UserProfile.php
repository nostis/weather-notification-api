<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    use TimestampableEntity;

    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue()]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $name;

    #[ORM\OneToOne(inversedBy: 'userProfile', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private User $userRelation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUserRelation(): ?User
    {
        return $this->userRelation;
    }

    public function setUserRelation(User $userRelation): self
    {
        $this->userRelation = $userRelation;

        return $this;
    }
}
