<?php

namespace App\Api\Tournament\Infrastructure\Persistence\Doctrine;

use App\Api\Tournament\Domain\Tournament;
use App\Api\Tournament\Domain\TournamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Api\Tournament\Infrastructure\Persistence\TournamentMapper;
use App\Api\Player\Domain\Player;
use App\Api\Player\Infrastructure\Persistence\Doctrine\PlayerEntity;
use Doctrine\Persistence\ObjectRepository;

class DoctrineTournamentRepository implements TournamentRepository
{
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(TournamentEntity::class);
    }

    public function save(Tournament $tournament): void
    {
        $tournamentEntity = TournamentMapper::toEntity($tournament);
        $this->entityManager->persist($tournamentEntity);
        $this->entityManager->flush();

        $tournament->setId($tournamentEntity->getId());
    }

    public function findById(int $id): ?Tournament
    {
        $tournamentEntity = $this->entityManager->getRepository(TournamentEntity::class)->find($id);

        return $tournamentEntity ? TournamentMapper::toDomain($tournamentEntity) : null;
    }

    public function findAll(): array
    {
        $tournamentEntities = $this->entityManager->getRepository(TournamentEntity::class)->findAll();

        return array_map(
            fn ($tournamentEntity) => TournamentMapper::toDomain($tournamentEntity),
            $tournamentEntities
        );
    }

    public function setWinner(int $tournamentId, Player $winner): void
    {
        $tournamentEntity = $this->repository->find($tournamentId);

        if (!$tournamentEntity) {
            throw new \Exception('Tournament not found.');
        }

        $winnerEntity = $this->entityManager->getRepository(PlayerEntity::class)->find($winner->getId());


        $tournamentEntity->setWinner($winnerEntity);
        $this->entityManager->flush();
    }

    public function findByFilters(array $filters, int $offset = 0, int $limit = 10): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select('t')
            ->from(TournamentEntity::class, 't')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // Filtros
        if (isset($filters['gender'])) {
            $queryBuilder->andWhere('t.gender = :gender')
                ->setParameter('gender', $filters['gender']);
        }

        if (isset($filters['createdAt'])) {
            $queryBuilder->andWhere('t.createdAt >= :createdAt')
                ->setParameter('createdAt', $filters['createdAt']);
        }

        return array_map(
            fn ($tournamentEntity) => TournamentMapper::toDomain($tournamentEntity),
            $queryBuilder->getQuery()->getResult()
        );
    }
}
