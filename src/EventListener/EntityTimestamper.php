<?php

namespace App\EventListener;

use App\Contract\Entity\Timestampable;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class EntityTimestamper
{
    public function prePersist(PrePersistEventArgs $event): void
    {
        $entity = $event->getObject();
        if (!$entity instanceof Timestampable) {
            return;
        }

        $now = new \DateTimeImmutable('now');
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $entity = $event->getObject();
        if (!$entity instanceof Timestampable) {
            return;
        }

        $now = new \DateTimeImmutable('now');
        $entity->setUpdatedAt($now);
    }
}
