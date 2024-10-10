<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ExceptionHandler
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = $this->handle($exception);
        $event->setResponse($response);
    }

    private function handle(\Throwable $exception): JsonResponse
    {
        if ($exception instanceof ApiException) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                $exception->getStatusCode()
            );
        }

        if ($exception instanceof ResourceNotFoundException) {
            return new JsonResponse(
                ['error' => 'Route not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof HttpExceptionInterface) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                $exception->getStatusCode()
            );
        }

        return new JsonResponse(
            ['error' => 'An unexpected error occurred'],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
