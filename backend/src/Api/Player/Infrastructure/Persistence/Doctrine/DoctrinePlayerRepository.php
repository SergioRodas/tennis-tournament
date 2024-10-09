<?php

namespace App\Api\Player\Infrastructure\Persistence\Doctrine;

use App\Api\Player\Domain\Player;
use App\Api\Player\Domain\PlayerRepository;
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
    }

    public function findById(int $id): ?Player
    {
        $playerEntity = $this->entityManager->getRepository(PlayerEntity::class)->find($id);

        return $playerEntity ? PlayerMapper::toDomain($playerEntity) : null;
    }

    public function findAllWithFilters(array $filters, int $page, int $limit): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(PlayerEntity::class, 'p')
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

        return $queryBuilder->getQuery()->getResult();
    }
}
