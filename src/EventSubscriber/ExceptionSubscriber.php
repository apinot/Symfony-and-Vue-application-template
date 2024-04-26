<?php

namespace App\EventSubscriber;

use App\Contract\Exception\ThrowableAsResponse;
use App\Contract\Http\HttpStatus;
use App\Contract\Http\Routing;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    use Routing;

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->isVueApiRequest($event->getRequest())) {
            return;
        }

        $exception = $event->getThrowable();
        $response = match (true) {
            $exception instanceof ThrowableAsResponse => $this->createJsonResponseFromHttpErrorException($exception),
            $exception instanceof SymfonyHttpException => $this->createJsonResponseFromOtherSymfonyHttpException($exception),
            default => null,
        };
        if (!$response) {
            return;
        }
        $event->setResponse($response);
    }

    private function createJsonResponseFromHttpErrorException(ThrowableAsResponse $httpErrorException): JsonResponse
    {
        $errorStatus = $httpErrorException->getHttpErrorStatus();
        $errorMessage = $httpErrorException->getHttpErrorMessage();
        return $this->createJsonErrorResponse($errorStatus, $errorMessage);
    }

    private function createJsonResponseFromOtherSymfonyHttpException(SymfonyHttpException $symfonyHttpException): ?JsonResponse
    {
        $previousHttpException = $symfonyHttpException->getPrevious();

        // We only support validation exception at the moment
        if (!$previousHttpException instanceof ValidationFailedException) {
            return null;
        }

        return $this->createJsonResponseFromValidationFailedException($previousHttpException);
    }

    private function createJsonResponseFromValidationFailedException(ValidationFailedException $validationFailedException): JsonResponse
    {
        $errorMessage = null;
        $allValidationsFails = $validationFailedException->getViolations();
        if ($allValidationsFails->has(0)) {
            $firstValidationFail = $allValidationsFails->get(0);
            $errorMessage = $firstValidationFail->getPropertyPath() . ' : ' . mb_strtolower($firstValidationFail->getMessage());
        }

        return $this->createJsonErrorResponse(HttpStatus::UNPROCESSABLE_ENTITY, $errorMessage);
    }

    private function createJsonErrorResponse(HttpStatus $httpStatus, ?string $message = null): JsonResponse
    {
        $errorSerialization = [
            'code' => $httpStatus->value,
            'name' => $httpStatus->getHumanReadableName(),
        ];

        if ($message) {
            $errorSerialization['message'] = $message;
        }

        return new JsonResponse(
            data: ['error' => $errorSerialization],
            status: $httpStatus->value,
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
