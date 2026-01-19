<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ResultatDTO
{
    #[Assert\NotNull]
    #[Assert\Range(min: 0, max: 3)]
    public int $scoreJoueur1;

    #[Assert\NotNull]
    #[Assert\Range(min: 0, max: 3)]
    public int $scoreJoueur2;

    #[Assert\NotNull]
    public int $rencontreId;
}
