<?php

namespace App\Api\Tournament\Application\UseCases;

use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class ListTournamentsUseCase
{
    public function __construct(private TournamentRepository $tournamentRepository)
    {
    }

    public function execute(array $filters = [], int $offset = 0, int $limit = 20, string $orderBy = 'createdAt', string $order = 'asc'): array
    {
        $filters['orderBy'] = $orderBy;
        $filters['order'] = $order;

        $tournaments = $this->tournamentRepository->findByFilters($filters, $offset, $limit);

        if (empty($tournaments)) {
            throw new ApiException('No tournaments found', Response::HTTP_NOT_FOUND);
        }

        return $tournaments;
    }
}
