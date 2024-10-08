<?php

namespace App\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Domain\Matchup;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class GetMatchupUseCase
{
    private MatchupRepository $matchupRepository;

    public function __construct(MatchupRepository $matchupRepository)
    {
        $this->matchupRepository = $matchupRepository;
    }

    public function execute(int $id): Matchup
    {
        $matchup = $this->matchupRepository->findById($id);

        if (!$matchup) {
            throw new ApiException('Matchup not found', Response::HTTP_NOT_FOUND);
        }

        return $matchup;
    }
}
