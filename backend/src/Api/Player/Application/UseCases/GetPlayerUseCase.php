<?php

namespace App\Api\Player\Application\UseCases;

use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;

class GetPlayerUseCase
{
    private PlayerRepository $repository;

    public function __construct(PlayerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id)
    {
        $player = $this->repository->findById($id);

        if (!$player) {
            throw new ApiException('Player not found', Response::HTTP_NOT_FOUND);
        }

        return $player;
    }
}
