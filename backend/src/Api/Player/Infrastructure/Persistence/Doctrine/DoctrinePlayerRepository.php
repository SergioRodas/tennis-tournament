<?php

namespace App\Api\Player\Infrastructure\Persistence\Doctrine;

use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
use App\Api\Tournament\Infrastructure\Persistence\Doctrine\TournamentEntity;
use Doctrine\ORM\EntityManagerInterface;
use App\Api\Player\Infrastructure\Persistence\PlayerMapper;

class DoctrinePlayerRepository implements PlayerRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(Player $player): void
    {
        $playerEntity = PlayerMapper::toEntity($player);
        $this->entityManager->persist($playerEntity);
        $this->entityManager->flush();

        $player->setId($playerEntity->getId());
    }

    public function findById(int $id): ?Player
    {
        $playerEntity = $this->entityManager->getRepository(PlayerEntity::class)->find($id);

        return $playerEntity ? PlayerMapper::toDomain($playerEntity) : null;
    }

    public function findAllWithFilters(array $filters, int $page, int $limit): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('p', 'COUNT(t.id) as tournamentsWon')
            ->from(PlayerEntity::class, 'p')
            ->leftJoin(TournamentEntity::class, 't', 'WITH', 't.winner = p')
            ->groupBy('p.id')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        // Aplicar los filtros opcionales de manera segura
        if (!empty($filters['skill'])) {
            $queryBuilder->andWhere('p.skillLevel = :skill')
                ->setParameter('skill', $filters['skill']);
        }

        if (!empty($filters['gender']) && in_array($filters['gender'], ['M', 'F'])) {
            $queryBuilder->andWhere('p.gender = :gender')
                ->setParameter('gender', $filters['gender']);
        }

        $queryBuilder->orderBy('p.id', 'ASC');

        $result = $queryBuilder->getQuery()->getResult();

        return array_map(function ($row) {
            $player = PlayerMapper::toDomain($row[0]);
            $player->setTournamentsWon($row['tournamentsWon']);

            return $player;
        }, $result);
    }

    public function countAllWithFilters(array $filters): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('COUNT(p.id)')
            ->from(PlayerEntity::class, 'p');

        // Aplicar los filtros opcionales de manera segura
        if (!empty($filters['skill'])) {
            $queryBuilder->andWhere('p.skillLevel = :skill')
                ->setParameter('skill', $filters['skill']);
        }

        if (!empty($filters['gender']) && in_array($filters['gender'], ['M', 'F'])) {
            $queryBuilder->andWhere('p.gender = :gender')
                ->setParameter('gender', $filters['gender']);
        }

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
