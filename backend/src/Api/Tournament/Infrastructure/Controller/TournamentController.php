<?php

namespace App\Api\Tournament\Infrastructure\Controller;

use App\Api\Tournament\Application\Dto\CreateTournamentRequestDto;
use App\Api\Tournament\Application\UseCases\CreateTournamentUseCase;
use App\Api\Tournament\Application\UseCases\GetTournamentUseCase;
use App\Api\Tournament\Application\UseCases\ListTournamentsUseCase;
use App\Api\Tournament\Application\UseCases\SimulateTournamentUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Shared\Domain\Exception\ApiException;

class TournamentController extends AbstractController
{
    private CreateTournamentUseCase $createTournamentUseCase;
    private GetTournamentUseCase $getTournamentUseCase;
    private SimulateTournamentUseCase $simulateTournamentUseCase;
    private ListTournamentsUseCase $listTournamentsUseCase;
    private ValidatorInterface $validator;

    public function __construct(
        CreateTournamentUseCase $createTournamentUseCase,
        GetTournamentUseCase $getTournamentUseCase,
        SimulateTournamentUseCase $simulateTournamentUseCase,
        ListTournamentsUseCase $listTournamentsUseCase,
        ValidatorInterface $validator,
    ) {
        $this->createTournamentUseCase = $createTournamentUseCase;
        $this->getTournamentUseCase = $getTournamentUseCase;
        $this->simulateTournamentUseCase = $simulateTournamentUseCase;
        $this->listTournamentsUseCase = $listTournamentsUseCase;
        $this->validator = $validator;
    }

    public function createTournament(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            throw new ApiException('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        $dto = new CreateTournamentRequestDto(
            strtoupper($data['gender'])
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new ApiException(json_encode(['errors' => $errorMessages]), Response::HTTP_BAD_REQUEST);
        }

        $tournament = $this->createTournamentUseCase->execute($dto->gender);

        return new JsonResponse([
            'message' => 'Tournament created successfully',
            'tournament' => $tournament->toArray(),
        ], Response::HTTP_CREATED);
    }

    public function getTournament(int $id): JsonResponse
    {
        $tournament = $this->getTournamentUseCase->execute($id);

        return new JsonResponse($tournament->toArray());
    }

    public function simulateTournament(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['tournament_id'])) {
            throw new ApiException('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        $winner = $this->simulateTournamentUseCase->execute($data['tournament_id']);

        return new JsonResponse([
            'message' => 'Tournament simulation successful',
            'winner' => $winner->toArray(),
        ], Response::HTTP_OK);
    }

    public function listTournaments(Request $request): JsonResponse
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = $request->query->getInt('limit', 20);
        $tournaments = $this->listTournamentsUseCase->execute([], $offset, $limit);

        return new JsonResponse(array_map(fn ($tournament) => $tournament->toArray(), $tournaments));
    }
}
