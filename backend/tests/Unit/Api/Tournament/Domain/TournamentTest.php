<?php

namespace Tests\Unit\Api\Tournament\Domain;

use App\Api\Tournament\Domain\Tournament;
use App\Api\Player\Domain\Player;
use PHPUnit\Framework\TestCase;

class TournamentTest extends TestCase
{
    private Tournament $tournament;

    protected function setUp(): void
    {
        $this->tournament = new Tournament('Test Tournament', 'M');
        $this->tournament->setId(1);
        $this->tournament->setCreatedAt(new \DateTime('2023-01-01'));
    }

    public function testTournamentCreation()
    {
        $this->assertInstanceOf(Tournament::class, $this->tournament);
        $this->assertEquals('Test Tournament', $this->tournament->getName());
        $this->assertEquals('M', $this->tournament->getGender());
        $this->assertEquals(1, $this->tournament->getId());
        $this->assertEquals('2023-01-01', $this->tournament->getCreatedAt()->format('Y-m-d'));
    }

    public function testSetAndGetWinner()
    {
        $winner = new Player('John Doe', 80, 'M', 70, 75);
        $this->tournament->setWinner($winner);
        $this->assertSame($winner, $this->tournament->getWinner());
    }

    public function testSetAndGetFinishedAt()
    {
        $finishedAt = new \DateTime('2023-12-31');
        $this->tournament->setFinishedAt($finishedAt);
        $this->assertEquals($finishedAt, $this->tournament->getFinishedAt());
    }

    public function testCanPlayersParticipate()
    {
        $player1 = new Player('John Doe', 80, 'M', 70, 75);
        $player2 = new Player('Jane Doe', 85, 'M', 65, 80);
        $player3 = new Player('Alice Smith', 75, 'F', 60, 70);

        $this->assertTrue($this->tournament->canPlayersParticipate($player1, $player2));
        $this->assertFalse($this->tournament->canPlayersParticipate($player1, $player3));
    }

    public function testToArray()
    {
        $winner = new Player('John Doe', 80, 'M', 70, 75);
        $winner->setId(1);
        $this->tournament->setWinner($winner);
        $this->tournament->setFinishedAt(new \DateTime('2023-12-31'));

        $expectedArray = [
            'id' => 1,
            'name' => 'Test Tournament',
            'gender' => 'M',
            'winner' => $winner->toArray(),
            'created_at' => '2023-01-01 00:00:00',
            'finished_at' => '2023-12-31 00:00:00',
        ];

        $this->assertEquals($expectedArray, $this->tournament->toArray());
    }
}
