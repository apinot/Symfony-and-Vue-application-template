<?php

namespace App\Contract\Http;

enum HttpStatus: int
{
    case OK = 200;
    case CREATED = 201;

    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case CONFLICT = 409;
    case UNPROCESSABLE_ENTITY = 422;

    public function getHumanReadableName(): string
    {
        return ucwords(str_replace('_', ' ', mb_strtolower($this->name)));
    }
}
