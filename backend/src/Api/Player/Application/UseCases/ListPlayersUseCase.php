<?php

namespace App\Api\Player\Application\UseCases;

use App\Api\Player\Application\Dto\PlayerFilterRequestDto;
use App\Api\Player\Domain\PlayerRepository;
use App\Shared\Domain\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ListPlayersUseCase
{
    private PlayerRepository $playerRepository;
    private ValidatorInterface $validator;

    public function __construct(PlayerRepository $playerRepository, ValidatorInterface $validator)
    {
        $this->playerRepository = $playerRepository;
        $this->validator = $validator;
    }

    public function execute(array $queryParams): array
    {
        $dto = new PlayerFilterRequestDto();
        $dto->page = $queryParams['page'] ?? 1;
        $dto->limit = $queryParams['limit'] ?? 20;
        $dto->skill = $queryParams['skill'] ?? null;
        $dto->gender = $queryParams['gender'] ?? null;

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            throw new ApiException(json_encode(['errors' => $errorMessages]), Response::HTTP_BAD_REQUEST);
        }

        $filters = [
            'skill' => $dto->skill,
            'gender' => null !== $dto->gender ? strtoupper($dto->gender) : null,
        ];

        $players = $this->playerRepository->findAllWithFilters($filters, $dto->page, $dto->limit);

        if (empty($players)) {
            throw new ApiException('No players found', Response::HTTP_NOT_FOUND);
        }

        return $players;
    }
}
