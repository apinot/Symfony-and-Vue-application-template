<?php

namespace App\Contract\Http;

use App\Contract\Serializer\Group;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AppController extends AbstractController
{
    use Routing;

    protected function getSerializerGroups(): array
    {
        return [self::class, static::class, Group::ALL_ROUTES];
    }

    protected function createJsonResponse(mixed $data, HttpStatus $status): JsonResponse
    {
        return $this->json(
            data: $data,
            status: $status->value,
            context: [
                'groups' => $this->getSerializerGroups(),
            ],
        );
    }
}
