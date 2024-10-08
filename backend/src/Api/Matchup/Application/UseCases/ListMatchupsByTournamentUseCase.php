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

    public function execute(array $params): array
    {
        $tournamentId = $params['tournament_id'] ?? null;
        $finished = $params['finished'] ?? null;

        if ($tournamentId === null) {
            throw new ApiException('Tournament ID is required', Response::HTTP_BAD_REQUEST);
        }

        if ($finished !== null) {
            $finished = $finished === 'true' ? true : ($finished === 'false' ? false : null);
        }

        $matchups = $this->matchupRepository->findByTournamentId($tournamentId, $finished);

        if (empty($matchups)) {
            throw new ApiException('No matchups found for this tournament', Response::HTTP_NOT_FOUND);
        }

        return [
            'tournament_id' => $tournamentId,
            'matchups' => $matchups
        ];
    }
}
