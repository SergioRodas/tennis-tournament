<?php

namespace App\Api\Player\Application\UseCases;

use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class ListPlayersUseCase
{
    private PlayerRepository $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function execute(array $filters, int $page, int $limit): array
    {
        $players = $this->playerRepository->findAllWithFilters($filters, $page, $limit);

        if (empty($players)) {
            throw new ApiException('No players found', Response::HTTP_NOT_FOUND);
        }

        return $players;
    }
}
