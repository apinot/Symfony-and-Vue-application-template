<?php

namespace App\Contract\Entity;

use Doctrine\ORM\EntityManagerInterface;

readonly class PersistanceService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function persistEntity(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function persistEntityThenFlush(object $entity): void
    {
        $this->persistEntity($entity);
        $this->flush();
    }
}
