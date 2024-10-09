<?php

namespace App\Api\Matchup\Infrastructure\Controller;

use App\Api\Matchup\Application\UseCases\CreateMatchupUseCase;
use App\Api\Matchup\Application\UseCases\GetMatchupUseCase;
use App\Api\Matchup\Application\UseCases\ListMatchupsByTournamentUseCase;
use App\Api\Matchup\Application\UseCases\UpdateMatchupWinnerUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Shared\Domain\Exception\ApiException;

class MatchupController extends AbstractController
{
    private CreateMatchupUseCase $createMatchupUseCase;
    private GetMatchupUseCase $getMatchupUseCase;
    private ListMatchupsByTournamentUseCase $listMatchupsByTournamentUseCase;
    private UpdateMatchupWinnerUseCase $updateMatchupWinnerUseCase;
    private ValidatorInterface $validator;

    public function __construct(
        CreateMatchupUseCase $createMatchupUseCase,
        GetMatchupUseCase $getMatchupUseCase,
        ListMatchupsByTournamentUseCase $listMatchupsByTournamentUseCase,
        UpdateMatchupWinnerUseCase $updateMatchupWinnerUseCase,
        ValidatorInterface $validator,
    ) {
        $this->createMatchupUseCase = $createMatchupUseCase;
        $this->getMatchupUseCase = $getMatchupUseCase;
        $this->listMatchupsByTournamentUseCase = $listMatchupsByTournamentUseCase;
        $this->updateMatchupWinnerUseCase = $updateMatchupWinnerUseCase;
        $this->validator = $validator;
    }

    public function createMatchup(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            throw new ApiException('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        $this->createMatchupUseCase->execute($data);

        return new JsonResponse(['message' => 'Matchup created successfully'], Response::HTTP_CREATED);
    }

    public function getMatchup(int $id): JsonResponse
    {
        $matchup = $this->getMatchupUseCase->execute($id);

        return new JsonResponse($matchup->toArray());
    }

    public function listByTournament(int $tournamentId, Request $request): JsonResponse
    {
        $params = [
            'tournament_id' => $tournamentId,
            'finished' => $request->query->get('finished'),
        ];

        $result = $this->listMatchupsByTournamentUseCase->execute($params);

        return new JsonResponse($result);
    }

    public function updateWinner(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['winner_id'])) {
            throw new ApiException('Winner ID is required', Response::HTTP_BAD_REQUEST);
        }

        $winnerId = (int) $data['winner_id'];
        $this->updateMatchupWinnerUseCase->execute($id, $winnerId);

        return new JsonResponse(['message' => 'Winner updated successfully']);
    }
}
