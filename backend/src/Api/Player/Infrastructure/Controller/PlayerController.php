<?php

namespace App\Api\Player\Infrastructure\Controller;

use App\Api\Player\Application\UseCases\ListPlayersUseCase;
use App\Api\Player\Application\UseCases\CreatePlayerUseCase;
use App\Api\Player\Application\UseCases\GetPlayerUseCase;
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

        $this->createPlayerUseCase->execute($data);

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
        $queryParams = $request->query->all();

        $players = $this->listPlayersUseCase->execute($queryParams);

        $playersArray = array_map(function ($player) {
            return $player->toArray();
        }, $players);

        return new JsonResponse($playersArray);
    }
}
