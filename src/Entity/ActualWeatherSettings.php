<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Api\ActualWeatherSettingsCreateController;
use App\Repository\ActualWeatherSettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ApiResource(
    collectionOperations: [
        'post' => ['controller' => ActualWeatherSettingsCreateController::class],
        'get' => ['security' => "is_granted('ROLE_USER') and object.user == user"],
    ],
    itemOperations: [
        'get' => ['security' => "is_granted('ROLE_USER') and object.user == user"],
        'patch' => ['security' => "is_granted('ROLE_USER') and object.user == user"],
        'delete' => ['security' => "is_granted('ROLE_USER') and object.user == user"]
    ],
    attributes: [
        'security' => "is_granted('ROLE_USER')"
    ],
    denormalizationContext: [
        'groups' => ['write']
    ],
    normalizationContext: [
        'groups' => ['read']
    ]
)]
#[ORM\Entity(repositoryClass: ActualWeatherSettingsRepository::class)]
class ActualWeatherSettings
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read'])]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'actualWeatherSettings')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: 'smallint')]
    #[Groups(['read', 'write'])]
    private int $dayOfTheWeek; //1 - 7

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read', 'write'])]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'h:i a'])]
    #[ApiProperty(
        attributes: [
            "openapi_context" => [
                "type" => "datetime",
                "example" => "05:00 am",
            ],
        ],
    )]
    private \DateTimeInterface $hour;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isActive = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDayOfTheWeek(): ?int
    {
        return $this->dayOfTheWeek;
    }

    public function setDayOfTheWeek(int $dayOfTheWeek): self
    {
        $this->dayOfTheWeek = $dayOfTheWeek;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
