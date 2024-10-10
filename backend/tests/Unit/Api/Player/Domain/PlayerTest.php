<?php

namespace Tests\Unit\Api\Player\Domain;

use App\Api\Player\Domain\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    public function testCreateMalePlayer()
    {
        $player = new Player('John Doe', 80, 'M', 70, 75);

        $this->assertEquals('John Doe', $player->getName());
        $this->assertEquals(80, $player->getSkillLevel());
        $this->assertEquals('M', $player->getGender());
        $this->assertEquals(70, $player->getStrength());
        $this->assertEquals(75, $player->getSpeed());
        $this->assertNull($player->getReactionTime());
    }

    public function testCreateFemalePlayer()
    {
        $player = new Player('Jane Doe', 85, 'F', null, null, 0.5);

        $this->assertEquals('Jane Doe', $player->getName());
        $this->assertEquals(85, $player->getSkillLevel());
        $this->assertEquals('F', $player->getGender());
        $this->assertNull($player->getStrength());
        $this->assertNull($player->getSpeed());
        $this->assertEquals(0.5, $player->getReactionTime());
    }

    public function testSetId()
    {
        $player = new Player('Test Player', 75, 'M');
        $player->setId(1);

        $this->assertEquals(1, $player->getId());
    }

    public function testToArray()
    {
        $player = new Player('Array Test', 90, 'F', null, null, 0.7);
        $player->setId(2);

        $expectedArray = [
            'id' => 2,
            'name' => 'Array Test',
            'skillLevel' => 90,
            'gender' => 'F',
            'strength' => null,
            'speed' => null,
            'reactionTime' => 0.7,
        ];

        $this->assertEquals($expectedArray, $player->toArray());
    }

    public function testInvalidGender()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Player('Invalid', 80, 'X');
    }

    public function testInvalidSkillLevel()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Player('Invalid', 101, 'M');
    }

    public function testCreatePlayerWithMinimumSkillLevel()
    {
        $player = new Player('Min Skill', 0, 'M');
        $this->assertEquals(0, $player->getSkillLevel());
    }

    public function testCreatePlayerWithMaximumSkillLevel()
    {
        $player = new Player('Max Skill', 100, 'M');
        $this->assertEquals(100, $player->getSkillLevel());
    }

    public function testCreateMalePlayerWithMinimumStrengthAndSpeed()
    {
        $player = new Player('Min Strength Speed', 50, 'M', 0, 0);
        $this->assertEquals(0, $player->getStrength());
        $this->assertEquals(0, $player->getSpeed());
    }

    public function testCreateMalePlayerWithMaximumStrengthAndSpeed()
    {
        $player = new Player('Max Strength Speed', 50, 'M', 100, 100);
        $this->assertEquals(100, $player->getStrength());
        $this->assertEquals(100, $player->getSpeed());
    }

    public function testCreateFemalePlayerWithMinimumReactionTime()
    {
        $player = new Player('Min Reaction', 50, 'F', null, null, 0);
        $this->assertEquals(0, $player->getReactionTime());
    }

    public function testCreateFemalePlayerWithMaximumReactionTime()
    {
        $player = new Player('Max Reaction', 50, 'F', null, null, 100);
        $this->assertEquals(100, $player->getReactionTime());
    }

    public function testInvalidStrength()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Player('Invalid Strength', 50, 'M', 101);
    }

    public function testInvalidSpeed()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Player('Invalid Speed', 50, 'M', 50, 101);
    }

    public function testInvalidReactionTime()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Player('Invalid Reaction', 50, 'F', null, null, 101);
    }
}
