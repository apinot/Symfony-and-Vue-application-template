<?php

namespace App\Contract\Security;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfService
{
    public const CSRF_TOKEN_ID = 'APP_CSRF_TOKEN';

    public function __construct(
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
    ) {
    }

    public function isTokenValid(string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken(self::CSRF_TOKEN_ID, $token));
    }

    public function generateTokenCookie(): Cookie
    {
        $token = $this->getOrGenerateToken();
        return new Cookie('XSRF-TOKEN', $token, httpOnly: false, sameSite: 'strict');
    }

    private function getOrGenerateToken(): string
    {
        return $this->csrfTokenManager->getToken(self::CSRF_TOKEN_ID);
    }
}
