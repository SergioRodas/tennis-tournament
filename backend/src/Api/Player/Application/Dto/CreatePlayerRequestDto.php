<?php

namespace App\Api\Player\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePlayerRequestDto
{
    /**
     * @Assert\NotBlank
     *
     * @Assert\Type(type="string")
     */
    public string $name;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Type(type="integer")
     *
     * @Assert\Range(min=0, max=100)
     */
    public int $skillLevel;

    /**
     * @Assert\NotBlank
     *
     * @Assert\Choice(choices={"M", "F"}, message="Choose a valid gender: M or F.")
     */
    public string $gender;

    /**
     * @Assert\Type(type="integer")
     *
     * @Assert\PositiveOrZero
     *
     * @Assert\LessThanOrEqual(100)
     */
    public ?int $strength = null;

    /**
     * @Assert\Type(type="integer")
     *
     * @Assert\PositiveOrZero
     *
     * @Assert\LessThanOrEqual(100)
     */
    public ?int $speed = null;

    /**
     * @Assert\Type(type="integer")
     *
     * @Assert\PositiveOrZero
     *
     * @Assert\LessThanOrEqual(100)
     */
    public ?int $reactionTime = null;
}
