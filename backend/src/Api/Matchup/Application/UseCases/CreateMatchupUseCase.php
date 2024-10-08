<?php

namespace App\Api\Matchup\Application\UseCases;

use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class CreateMatchupUseCase
{
    private MatchupRepository $matchupRepository;
    private PlayerRepository $playerRepository;
    private TournamentRepository $tournamentRepository;

    public function __construct(
        MatchupRepository $matchupRepository,
        PlayerRepository $playerRepository,
        TournamentRepository $tournamentRepository,
    ) {
        $this->matchupRepository = $matchupRepository;
        $this->playerRepository = $playerRepository;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function execute(int $player1Id, int $player2Id, int $tournamentId): void
    {
        // Buscar los jugadores y el torneo por ID
        $player1 = $this->playerRepository->findById($player1Id);
        $player2 = $this->playerRepository->findById($player2Id);
        $tournament = $this->tournamentRepository->findById($tournamentId);

        if (!$player1 || !$player2 || !$tournament) {
            throw new ApiException('Invalid players or tournament', Response::HTTP_NOT_FOUND);
        }

        // El torneo valida si los jugadores pueden participar en un matchup
        if (!$tournament->canPlayersParticipate($player1, $player2)) {
            throw new ApiException('Players do not meet the tournament requirements', Response::HTTP_BAD_REQUEST);
        }

        // Guardar el Matchup en el repositorio
        $this->matchupRepository->save($player1Id, $player2Id, $tournamentId);
    }
}
