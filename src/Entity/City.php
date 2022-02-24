<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        'get'
    ],
    itemOperations: [
        'get'
    ],
    normalizationContext: [
        'groups' => ['read']
    ]
)]

#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read'])]
    private string $name;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 8)]
    #[Groups(['read'])]
    private string $lat;

    #[ORM\Column(type: 'decimal', precision: 11, scale: 8)]
    #[Groups(['read'])]
    private string $lng;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: UserProfile::class)]
    private Collection $userProfiles;

    public function __construct()
    {
        $this->userProfiles = new ArrayCollection();
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

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return Collection|UserProfile[]
     */
    public function getUserProfiles(): Collection
    {
        return $this->userProfiles;
    }

    public function addUserProfile(UserProfile $userProfile): self
    {
        if (!$this->userProfiles->contains($userProfile)) {
            $this->userProfiles[] = $userProfile;
            $userProfile->setCity($this);
        }

        return $this;
    }

    public function removeUserProfile(UserProfile $userProfile): self
    {
        if ($this->userProfiles->removeElement($userProfile)) {
            // set the owning side to null (unless already changed)
            if ($userProfile->getCity() === $this) {
                $userProfile->setCity(null);
            }
        }

        return $this;
    }
}
