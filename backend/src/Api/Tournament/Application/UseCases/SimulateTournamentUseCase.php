<?php

namespace App\Api\Tournament\Application\UseCases;

use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\TournamentRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class SimulateTournamentUseCase
{
    private MatchupRepository $matchupRepository;
    private TournamentRepository $tournamentRepository;

    public function __construct(MatchupRepository $matchupRepository, TournamentRepository $tournamentRepository)
    {
        $this->matchupRepository = $matchupRepository;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function execute(int $tournamentId): Player
    {
        $tournament = $this->tournamentRepository->findById($tournamentId);
        if (!$tournament) {
            throw new ApiException('Tournament not found', Response::HTTP_NOT_FOUND);
        }

        if (null !== $tournament->getWinner()) {
            throw new ApiException('Tournament has already finished', Response::HTTP_BAD_REQUEST);
        }

        $gender = $tournament->getGender();
        $matchups = $this->matchupRepository->findByTournamentId($tournamentId, false);

        $count = count($matchups);
        if ($count < 2 || ($count & ($count - 1)) !== 0 || $count > 32) {
            throw new ApiException('Invalid number of matchups', Response::HTTP_BAD_REQUEST);
        }

        $winner = null;
        while (count($matchups) >= 1) {
            $nextRound = [];

            foreach ($matchups as $matchup) {
                $winner = $this->simulateMatchup($matchup->getPlayer1(), $matchup->getPlayer2(), $gender);
                $this->matchupRepository->updateWinner($matchup->getId(), $winner->getId());
                $nextRound[] = $winner;
            }

            for ($i = 0; $i < count($nextRound); $i += 2) {
                if (isset($nextRound[$i + 1])) {
                    $this->matchupRepository->save($nextRound[$i]->getId(), $nextRound[$i + 1]->getId(), $tournamentId);
                }
            }

            $matchups = $this->matchupRepository->findByTournamentId($tournamentId, false);
        }

        if ($winner) {
            $this->tournamentRepository->setWinner($tournamentId, $winner);
        }

        return $winner;
    }

    private function simulateMatchup(Player $player1, Player $player2, string $gender): Player
    {
        $score1 = $this->calculatePlayerScore($player1, $gender);
        $score2 = $this->calculatePlayerScore($player2, $gender);

        return $score1 > $score2 ? $player1 : $player2;
    }

    private function calculatePlayerScore(Player $player, string $gender): float
    {
        $baseScore = $player->getSkillLevel();
        $luckFactor = mt_rand(0, 20) / 100; // Luck factor between 0 and 0.2

        if ('M' === $gender) {
            $strengthFactor = $player->getStrength() / 100;
            $speedFactor = $player->getSpeed() / 100;

            return $baseScore * (1 + $luckFactor) * (1 + $strengthFactor) * (1 + $speedFactor);
        } elseif ('F' === $gender) {
            $reactionTimeFactor = $player->getReactionTime() / 100;

            return $baseScore * (1 + $luckFactor) * (1 + $reactionTimeFactor);
        }

        throw new \InvalidArgumentException('Invalid gender');
    }
}
