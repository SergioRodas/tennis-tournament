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
        $dto->page = max(1, $queryParams['page'] ?? 1); // Ensure page is at least 1
        $dto->limit = min(20, $queryParams['limit'] ?? 50); // Apply maximum limit of 50
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
            return [
                'players' => [],
                'pagination' => [
                    'totalItems' => 0,
                    'itemsPerPage' => $dto->limit,
                    'currentPage' => $dto->page,
                    'totalPages' => 0,
                ],
            ];
        }

        $totalPlayers = $this->playerRepository->countAllWithFilters($filters);
        $totalPages = ceil($totalPlayers / $dto->limit);

        return [
            'players' => $players,
            'pagination' => [
                'totalItems' => (int) $totalPlayers,
                'itemsPerPage' => (int) $dto->limit,
                'currentPage' => (int) $dto->page,
                'totalPages' => (int) $totalPages,
            ],
        ];
    }
}
