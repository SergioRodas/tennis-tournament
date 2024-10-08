<?php

namespace App\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Domain\MatchupRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class ListMatchupsByTournamentUseCase
{
    private MatchupRepository $matchupRepository;

    public function __construct(MatchupRepository $matchupRepository)
    {
        $this->matchupRepository = $matchupRepository;
    }

    public function execute(int $tournamentId, ?bool $finished = null): array
    {
        $matchups = $this->matchupRepository->findByTournamentId($tournamentId, $finished);

        if (empty($matchups)) {
            throw new ApiException('No matchups found for this tournament', Response::HTTP_NOT_FOUND);
        }

        return $matchups;
    }
}
