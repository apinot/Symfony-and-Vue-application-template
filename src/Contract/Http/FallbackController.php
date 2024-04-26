<?php

namespace App\Contract\Http;

use App\Contract\Exception\InvalidVueApiRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FallbackController extends AppController
{
    public function __invoke(Request $request): Response
    {
        if ($this->isVueApiRequest($request)) {
            throw new InvalidVueApiRequestException();
        }

        return $this->render('vue.html.twig');
    }
}
