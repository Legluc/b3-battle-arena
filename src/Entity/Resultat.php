<?php

namespace App\Entity;

use App\Repository\ResultatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ResultatRepository::class)]
class Resultat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['resultat:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 3, notInRangeMessage: "Le score doit être entre 0 et 3.")]
    #[Groups(['resultat:read', 'resultat:write'])]
    private int $scoreJoueur1;
    
    #[ORM\Column]
    #[Assert\Range(min: 0, max: 3, notInRangeMessage: "Le score doit être entre 0 et 3.")]
    #[Groups(['resultat:read', 'resultat:write'])]
    private int $scoreJoueur2 ;
    
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['resultat:read'])]
    private ?Rencontre $rencontre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScoreJoueur1(): ?int
    {
        return $this->scoreJoueur1;
    }

    public function setScoreJoueur1(int $scoreJoueur1): static
    {
        $this->scoreJoueur1 = $scoreJoueur1;

        return $this;
    }

    public function getScoreJoueur2(): ?int
    {
        return $this->scoreJoueur2;
    }

    public function setScoreJoueur2(int $scoreJoueur2): static
    {
        $this->scoreJoueur2 = $scoreJoueur2;

        return $this;
    }

    public function getRencontre(): ?Rencontre
    {
        return $this->rencontre;
    }

    public function setRencontre(Rencontre $rencontre): static
    {
        $this->rencontre = $rencontre;

        return $this;
    }
}
