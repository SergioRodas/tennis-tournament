<?php

namespace App\Api\Player\Application\UseCases;

use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class CreatePlayerUseCase
{
    private PlayerRepository $repository;

    public function __construct(PlayerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $name, int $skillLevel, string $gender, ?int $strength, ?int $speed, ?int $reactionTime)
    {
        try {
            $player = new Player($name, $skillLevel, $gender, $strength, $speed, $reactionTime);
            $this->repository->save($player);
        } catch (\Exception $e) {
            throw new ApiException('Failed to create player: '.$e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
