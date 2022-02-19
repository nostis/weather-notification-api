<?php

namespace App\Entity\Interface;

use App\Entity\User;

interface NotificationChannelInterface
{
    public function getUser(): ?User;

    public function setUser(?User $user): self;

    public function getIsActive(): ?bool;

    public function setIsActive(bool $isActive): self;
}
