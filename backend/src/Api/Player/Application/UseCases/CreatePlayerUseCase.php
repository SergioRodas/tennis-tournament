<?php

namespace App\Api\Player\Application\UseCases;

use App\Api\Player\Application\Dto\CreatePlayerRequestDto;
use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreatePlayerUseCase
{
    private PlayerRepository $repository;
    private ValidatorInterface $validator;

    public function __construct(PlayerRepository $repository, ValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function execute(array $data): Player
    {
        $dto = new CreatePlayerRequestDto();
        $dto->name = $data['name'] ?? null;
        $dto->skillLevel = $data['skillLevel'] ?? null;
        $dto->gender = strtoupper($data['gender'] ?? '');
        $dto->strength = $data['strength'] ?? null;
        $dto->speed = $data['speed'] ?? null;
        $dto->reactionTime = isset($data['reactionTime']) ? (float) $data['reactionTime'] : null;

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new ApiException(json_encode(['errors' => $errorMessages]), Response::HTTP_BAD_REQUEST);
        }

        try {
            $player = new Player(
                $dto->name,
                $dto->skillLevel,
                $dto->gender,
                $dto->strength,
                $dto->speed,
                $dto->reactionTime
            );
        } catch (\InvalidArgumentException $e) {
            throw new ApiException($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            throw new ApiException('Failed to create player: '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $this->repository->save($player);
        } catch (\Exception $e) {
            throw new ApiException('Failed to save player: '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $player;
    }
}
