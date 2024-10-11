<?php

namespace App\Api\Tournament\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateTournamentRequestDto
{
    /**
     * @Assert\NotBlank(message="The name should not be blank.")
     *
     * @Assert\Type("string", message="The name must be a string.")
     */
    public string $name;

    /**
     * @Assert\NotBlank(message="The gender should not be blank.")
     *
     * @Assert\Choice(choices={"M", "F"}, message="Invalid gender. Allowed values are 'M' or 'F'.")
     */
    public string $gender;

    public function __construct(?string $name, ?string $gender)
    {
        $this->name = $name ?? '';
        $this->gender = $gender ?? '';
    }
}
