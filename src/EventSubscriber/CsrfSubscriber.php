<?php

namespace App\EventSubscriber;

use App\Contract\Http\Routing;
use App\Contract\Security\CsrfService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CsrfSubscriber implements EventSubscriberInterface
{
    use Routing;

    private const string CSRF_HEADER = 'X-XSRF-TOKEN';

    public function __construct(
        private readonly CsrfService $csrfService,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$this->isVueApiRequest($request)) {
            return;
        }

        $csrfToken = $this->getCsrfTokenFromRequestHeader($request);
        $csrfTokenIsValid = $this->checkCsrfToken($csrfToken);
        if ($csrfTokenIsValid) {
            return;
        }

        $invalidCsrfResponse = new JsonResponse(
            ['error' => [
                'code' => 419,
            ]],
            419
        );
        $event->setResponse($invalidCsrfResponse);
    }

    private function getCsrfTokenFromRequestHeader(Request $request): string
    {
        return strval($request->headers->get(self::CSRF_HEADER));
    }

    private function checkCsrfToken(string $csrfToken): bool
    {
        return $this->csrfService->isTokenValid($csrfToken);
    }

    public static function getSubscribedEvents(): array
    {
        /*
         * Priority should be higher than the Auth firewall priority to
         * prevent login happen before the CSRF check.
         *
         *  /!\ WARNING /!\
         * If this priority is LESS THAN the Auth firewall priority,
         * the USER WILL STILL BE AUTHENTICATED
         * even IF the CSRF TOKEN IS INCORRECT and a 419 response is sent!
         *
         * More details: https://stackoverflow.com/questions/46275065/symfony-pre-login-event-listener
         */
        return [
            KernelEvents::REQUEST => ['onKernelRequest', self::getPriority()],
        ];
    }

    private static function getPriority(): int
    {
        return 9;
    }
}
