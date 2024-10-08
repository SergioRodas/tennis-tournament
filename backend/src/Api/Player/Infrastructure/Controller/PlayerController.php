<?php

namespace App\Api\Player\Infrastructure\Controller;

use App\Api\Player\Application\UseCases\ListPlayersUseCase;
use App\Api\Player\Application\UseCases\CreatePlayerUseCase;
use App\Api\Player\Application\UseCases\GetPlayerUseCase;
use App\Api\Player\Application\Dto\CreatePlayerRequestDto;
use App\Api\Player\Application\Dto\PlayerFilterRequestDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Shared\Domain\Exception\ApiException;

class PlayerController extends AbstractController
{
    private ListPlayersUseCase $listPlayersUseCase;
    private GetPlayerUseCase $getPlayerUseCase;
    private CreatePlayerUseCase $createPlayerUseCase;
    private ValidatorInterface $validator;

    public function __construct(
        ListPlayersUseCase $listPlayersUseCase,
        CreatePlayerUseCase $createPlayerUseCase,
        GetPlayerUseCase $getPlayerUseCase,
        ValidatorInterface $validator,
    ) {
        $this->listPlayersUseCase = $listPlayersUseCase;
        $this->createPlayerUseCase = $createPlayerUseCase;
        $this->getPlayerUseCase = $getPlayerUseCase;
        $this->validator = $validator;
    }

    public function createPlayer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            throw new ApiException('Invalid data', Response::HTTP_BAD_REQUEST);
        }

        $dto = new CreatePlayerRequestDto();
        $dto->name = $data['name'] ?? null;
        $dto->skillLevel = $data['skillLevel'] ?? null;
        $dto->gender = strtoupper($data['gender']) ?? null;
        $dto->strength = $data['strength'] ?? null;
        $dto->speed = $data['speed'] ?? null;
        $dto->reactionTime = $data['reactionTime'] ?? null;

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new ApiException(json_encode(['errors' => $errorMessages]), Response::HTTP_BAD_REQUEST);
        }

        $this->createPlayerUseCase->execute(
            $dto->name,
            $dto->skillLevel,
            $dto->gender,
            $dto->strength,
            $dto->speed,
            $dto->reactionTime
        );

        return new JsonResponse(['message' => 'Player created'], Response::HTTP_CREATED);
    }

    public function getPlayer(int $id): JsonResponse
    {
        $player = $this->getPlayerUseCase->execute($id);

        if (!$player) {
            throw new ApiException('Player not found', Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($player->toArray());
    }

    public function listPlayers(Request $request): JsonResponse
    {
        $filterRequest = new PlayerFilterRequestDto();
        $filterRequest->page = $request->query->getInt('page', 1);
        $filterRequest->limit = $request->query->getInt('limit', 20);
        $filterRequest->skill = $request->query->get('skill');
        $filterRequest->gender = $request->query->get('gender');

        $errors = $this->validator->validate($filterRequest);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new ApiException(json_encode(['errors' => $errorMessages]), Response::HTTP_BAD_REQUEST);
        }

        $filters = [
            'skill' => $filterRequest->skill,
            'gender' => strtoupper($filterRequest->gender),
        ];

        $players = $this->listPlayersUseCase->execute($filters, $filterRequest->page, $filterRequest->limit);

        $playersArray = array_map(function ($player) {
            return $player->toArray();
        }, $players);

        return new JsonResponse($playersArray);
    }
}
