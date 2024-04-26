<?php

namespace App\Contract\Exception;

use App\Contract\Http\HttpStatus;

class InvalidVueApiRequestException extends \Exception implements ThrowableAsResponse
{
    public function getHttpErrorStatus(): HttpStatus
    {
        return HttpStatus::BAD_REQUEST;
    }

    public function getHttpErrorMessage(): ?string
    {
        return null;
    }
}
