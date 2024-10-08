<?php

namespace App\Api\Tournament\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTournamentRequestDto
{
    /**
     * @Assert\NotBlank
     *
     * @Assert\Choice(choices={"M", "F"}, message="Invalid gender. Allowed values are 'M' or 'F'.")
     */
    public string $gender;

    public function __construct(string $gender)
    {
        $this->gender = $gender;
    }
}
