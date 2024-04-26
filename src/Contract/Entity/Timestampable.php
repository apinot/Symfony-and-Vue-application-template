<?php

namespace App\Contract\Entity;

interface Timestampable
{
    public function getCreatedAt(): \DateTimeImmutable;

    public function setCreatedAt(\DateTimeImmutable $datetimeCustom): static;

    public function getUpdatedAt(): \DateTimeImmutable;

    public function setUpdatedAt(\DateTimeImmutable $datetimeCustom): static;
}
