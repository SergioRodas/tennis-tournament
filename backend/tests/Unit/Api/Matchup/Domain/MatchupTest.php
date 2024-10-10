<?php

namespace Tests\Unit\Api\Matchup\Domain;

use App\Api\Matchup\Domain\Matchup;
use App\Api\Player\Domain\Player;
use App\Api\Tournament\Domain\Tournament;
use PHPUnit\Framework\TestCase;

class MatchupTest extends TestCase
{
    private Player $player1;
    private Player $player2;
    private Tournament $tournament;
    private Matchup $matchup;

    protected function setUp(): void
    {
        $this->player1 = new Player('John Doe', 80, 'M', 70, 75);
        $this->player1->setId(1);
        $this->player2 = new Player('Jane Doe', 85, 'F', null, null, 0.5);
        $this->player2->setId(2);
        $this->tournament = $this->createMock(Tournament::class);
        $this->matchup = new Matchup($this->player1, $this->player2, $this->tournament);
        $this->matchup->setId(1);
    }

    public function testMatchupCreation()
    {
        $this->assertInstanceOf(Matchup::class, $this->matchup);
        $this->assertEquals($this->player1, $this->matchup->getPlayer1());
        $this->assertEquals($this->player2, $this->matchup->getPlayer2());
        $this->assertEquals($this->tournament, $this->matchup->getTournament());
        $this->assertNull($this->matchup->getWinner());
    }

    public function testSetAndGetId()
    {
        $this->assertEquals(1, $this->matchup->getId());
        $this->matchup->setId(2);
        $this->assertEquals(2, $this->matchup->getId());
    }

    public function testSetAndGetWinner()
    {
        $this->assertNull($this->matchup->getWinner());
        $this->matchup->setWinner($this->player1);
        $this->assertEquals($this->player1, $this->matchup->getWinner());
    }

    public function testToArray()
    {
        $expectedArray = [
            'matchup_id' => 1,
            'winner_id' => null,
            'player1' => $this->player1->toArray(),
            'player2' => $this->player2->toArray(),
        ];

        $this->assertEquals($expectedArray, $this->matchup->toArray());

        $this->matchup->setWinner($this->player1);
        $expectedArray['winner_id'] = 1;

        $this->assertEquals($expectedArray, $this->matchup->toArray());
    }
}
