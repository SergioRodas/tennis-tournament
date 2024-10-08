<?php

namespace App\Api\Player\Domain;

interface PlayerRepository
{
    public function save(Player $player): void;

    public function findById(int $id): ?Player;

    public function findAllWithFilters(array $filters, int $page, int $limit): array;
}
