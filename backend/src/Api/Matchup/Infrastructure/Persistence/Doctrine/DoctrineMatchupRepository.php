<?php

namespace App\Api\Matchup\Infrastructure\Persistence\Doctrine;

use App\Api\Matchup\Domain\Matchup;
use App\Api\Matchup\Domain\MatchupRepository;
use App\Api\Matchup\Infrastructure\Persistence\MatchupMapper;
use App\Api\Player\Infrastructure\Persistence\Doctrine\PlayerEntity;
use App\Api\Tournament\Infrastructure\Persistence\Doctrine\TournamentEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrineMatchupRepository implements MatchupRepository
{
    private EntityManagerInterface $entityManager;
    private ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(MatchupEntity::class);
    }

    public function save(int $player1Id, int $player2Id, int $tournamentId): void
    {
        // Obtener las entidades de jugadores y torneo directamente
        $player1Entity = $this->entityManager->getRepository(PlayerEntity::class)->find($player1Id);
        $player2Entity = $this->entityManager->getRepository(PlayerEntity::class)->find($player2Id);
        $tournamentEntity = $this->entityManager->getRepository(TournamentEntity::class)->find($tournamentId);

        // Validar que las entidades existan
        if (!$player1Entity || !$player2Entity || !$tournamentEntity) {
            throw new \Exception('Invalid players or tournament');
        }

        // Crear la entidad Matchup y persistir
        $matchupEntity = new MatchupEntity($player1Entity, $player2Entity);
        $matchupEntity->setTournament($tournamentEntity);

        $this->entityManager->persist($matchupEntity);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Matchup
    {
        $matchupEntity = $this->repository->find($id);

        return $matchupEntity ? MatchupMapper::toDomain($matchupEntity) : null;
    }

    public function findAll(): array
    {
        $matchupEntities = $this->repository->findAll();

        return array_map([MatchupMapper::class, 'toDomain'], $matchupEntities);
    }

    public function findByTournamentId(int $tournamentId, ?bool $finished = null): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select('m')
            ->from(MatchupEntity::class, 'm')
            ->where('m.tournament = :tournamentId')
            ->setParameter('tournamentId', $tournamentId);

        if (true === $finished) {
            $queryBuilder->andWhere('m.winner IS NOT NULL');
        } elseif (false === $finished) {
            $queryBuilder->andWhere('m.winner IS NULL');
        }

        $matchupEntities = $queryBuilder->getQuery()->getResult();

        return array_map([MatchupMapper::class, 'toDomain'], $matchupEntities);
    }

    public function updateWinner(int $matchupId, int $winnerId): void
    {
        $matchupEntity = $this->repository->find($matchupId);

        if (!$matchupEntity) {
            throw new \Exception('Matchup not found');
        }

        $winnerEntity = $this->entityManager->getRepository(PlayerEntity::class)->find($winnerId);

        if (!$winnerEntity) {
            throw new \Exception('Winner not found');
        }

        $matchupEntity->setWinner($winnerEntity);
        $this->entityManager->flush();
    }
}
