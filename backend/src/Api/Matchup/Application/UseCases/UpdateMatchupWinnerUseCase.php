<?php

namespace App\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class UpdateMatchupWinnerUseCase
{
    private MatchupRepository $matchupRepository;
    private PlayerRepository $playerRepository;

    public function __construct(
        MatchupRepository $matchupRepository,
        PlayerRepository $playerRepository,
    ) {
        $this->matchupRepository = $matchupRepository;
        $this->playerRepository = $playerRepository;
    }

    public function execute(int $matchupId, int $winnerId): void
    {
        $matchup = $this->matchupRepository->findById($matchupId);
        if (!$matchup) {
            throw new ApiException('Matchup not found', Response::HTTP_NOT_FOUND);
        }

        $winner = $this->playerRepository->findById($winnerId);

        if (!$winner) {
            throw new ApiException('Winner not found', Response::HTTP_NOT_FOUND);
        }

        $this->matchupRepository->updateWinner($matchupId, $winnerId);
    }
}
