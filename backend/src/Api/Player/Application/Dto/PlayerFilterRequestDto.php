<?php

namespace App\Api\Player\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PlayerFilterRequestDto
{
    /**
     * @Assert\Type(type="integer")
     *
     * @Assert\PositiveOrZero
     *
     * @Assert\LessThanOrEqual(100)
     */
    public $page;

    /**
     * @Assert\Type(type="integer")
     *
     * @Assert\PositiveOrZero
     */
    public $limit;

    /**
     * @Assert\Type(type="string")
     *
     * @Assert\Length(max=50)
     *
     * @Assert\Regex(pattern="/^[a-zA-Z]+$/", message="Skill must contain only letters.")
     */
    public $skill;

    /**
     * @Assert\Type(type="string")
     *
     * @Assert\Choice(choices={"M", "F"}, message="Choose a valid gender: M or F.")
     */
    public $gender;
}
