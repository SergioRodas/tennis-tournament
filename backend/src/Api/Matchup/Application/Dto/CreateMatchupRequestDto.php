<?php

namespace App\Api\Matchup\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateMatchupRequestDto
{
    /**
     * @Assert\NotBlank
     *
     * @Assert\Type(type="integer")
     *
     * @Assert\Positive
     */
    public int $player1Id;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Type(type="integer")
     *
     * @Assert\Positive
     */
    public int $player2Id;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Type(type="integer")
     *
     * @Assert\Positive
     */
    public int $tournamentId;

    public function __construct(int $player1Id, int $player2Id, int $tournamentId)
    {
        $this->player1Id = $player1Id;
        $this->player2Id = $player2Id;
        $this->tournamentId = $tournamentId;
    }
}
