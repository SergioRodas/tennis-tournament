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

        if (null === $tournamentId) {
            throw new ApiException('Tournament ID is required', Response::HTTP_BAD_REQUEST);
        }

        if (null !== $finished) {
            $finished = 'true' === $finished ? true : ('false' === $finished ? false : null);
        }

        $matchups = $this->matchupRepository->findByTournamentId($tournamentId, $finished);

        if (empty($matchups)) {
            throw new ApiException('No matchups found for this tournament', Response::HTTP_NOT_FOUND);
        }
        $matchupsArray = array_map(function ($matchup) {
            return $matchup->toArray();
        }, $matchups);

        return [
            'tournament_id' => $tournamentId,
            'matchups' => $matchupsArray,
        ];
    }
}
