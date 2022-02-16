<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Api\ConfirmAccountController;
use App\Controller\Api\UserPasswordResetRequestController;
use App\Controller\Api\UserResetPasswordController;
use App\Dto\User\UserConfirmAccountInput;
use App\Dto\User\UserPasswordResetRequestInput;
use App\Dto\User\UserRegisterInput;
use App\Dto\User\UserAccountOutput;
use App\Dto\User\UserProfileUpdateInput;
use App\Dto\User\UserResetPasswordInput;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ApiResource(
    collectionOperations: [
        'create' => [
            'method' => 'POST',
            'path' => '/users/create_account',
            'input' => UserRegisterInput::class,
            'output' => UserAccountOutput::class
        ],
        'confirm_account' => [
            'method' => 'POST',
            'path' => '/users/confirm_account',
            'input' => UserConfirmAccountInput::class,
            'output' => UserAccountOutput::class,
            'receive' => false, //bypass default data transformer - after this option custom controller will receive custom input without transforming -> GeneralDtoInputDataTransformer
            'controller' => ConfirmAccountController::class
        ],
        'request_password_reset' => [
            'method' => 'POST',
            'path' => '/users/password_reset_request',
            'input' => UserPasswordResetRequestInput::class,
            'output' => false,
            'receive' => false,
            'controller' => UserPasswordResetRequestController::class
        ],
        'reset_password' => [
            'method' => 'POST',
            'path' => '/users/reset_password',
            'input' => UserResetPasswordInput::class,
            'output' => UserAccountOutput::class,
            'receive' => false,
            'controller' => UserResetPasswordController::class
        ]
    ],
    itemOperations: [
        'patch' => [
            'method' => 'PATCH',
            'input' => UserProfileUpdateInput::class,
            'output' => UserAccountOutput::class,
            'security' => "is_granted('ROLE_USER') and object == user"
        ],
        'get' => [
            'controller' => NotFoundAction::class,
            'read' => false,
            'output' => false,
        ]
    ]
)]

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue()]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 255)]
    private string $accountConfirmationToken;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $passwordResetToken;

    #[ORM\Column(type: 'boolean', options: ['default' => 0])]
    private bool $isConfirmed = false;

    #[ORM\Column(type: 'boolean', options: ['default' => 0])]
    private bool $isEnabled = false;

    #[ORM\OneToOne(mappedBy: 'userRelation', targetEntity: UserProfile::class, cascade: ['persist', 'remove'])]
    private UserProfile $userProfile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAccountConfirmationToken(): ?string
    {
        return $this->accountConfirmationToken;
    }

    public function setAccountConfirmationToken(string $accountConfirmationToken): self
    {
        $this->accountConfirmationToken = $accountConfirmationToken;

        return $this;
    }

    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(?string $passwordResetToken): self
    {
        $this->passwordResetToken = $passwordResetToken;

        return $this;
    }

    public function getIsConfirmed(): ?bool
    {
        return $this->isConfirmed;
    }

    public function setIsConfirmed(bool $isConfirmed): self
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $userProfile): self
    {
        // set the owning side of the relation if necessary
        if ($userProfile->getUserRelation() !== $this) {
            $userProfile->setUserRelation($this);
        }

        $this->userProfile = $userProfile;

        return $this;
    }
}
