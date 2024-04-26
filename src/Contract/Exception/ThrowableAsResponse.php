<?php

namespace App\Contract\Exception;

use App\Contract\Http\HttpStatus;

interface ThrowableAsResponse
{
    public function getHttpErrorStatus(): HttpStatus;

    public function getHttpErrorMessage(): ?string;
}
