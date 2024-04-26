<?php

namespace App\Contract\Http;

use Symfony\Component\HttpFoundation\Request;

trait Routing
{
    protected const VUE_API_PREFIX = '/vue-api';

    public function isVueApiRequest(Request $request): bool
    {
        $currentPath = $request->getPathInfo();
        return str_starts_with($currentPath, self::VUE_API_PREFIX);
    }
}
