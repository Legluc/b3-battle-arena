<?php

namespace App\Entity;

use App\Repository\RencontreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: RencontreRepository::class)]
class Rencontre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rencontre:read', 'resultat:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Joueur::class, inversedBy: 'rencontres')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Le joueur 1 ne peut pas être vide.")]
    #[Groups(['rencontre:read', 'rencontre:write'])]
    private ?Joueur $joueur1 = null;
    
    #[ORM\ManyToOne(targetEntity: Joueur::class, inversedBy: 'rencontres')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: "Le joueur 2 ne peut pas être vide.")]
        #[Groups(['rencontre:read', 'rencontre:write'])]
    private ?Joueur $joueur2 = null;

    #[ORM\ManyToOne(targetEntity: Joueur::class, inversedBy: 'rencontres')]
    #[Groups(['rencontre:read', 'rencontre:write'])]
    private ?Joueur $gagnant = null;

    #[ORM\Column(nullable: true)]
    private ?string $pdfLink = null;

    public function validateJoueurs(ExecutionContextInterface $context)
    {
        if ($this->getJoueur1() === $this->getJoueur2()) {
            $context->buildViolation('Un joueur ne peut pas s\'affronter lui-même.')
                ->atPath('joueur2')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueur1(): ?Joueur
    {
        return $this->joueur1;
    }

    public function setJoueur1(?Joueur $joueur1): static
    {
        $this->joueur1 = $joueur1;

        return $this;
    }

    public function getJoueur2(): ?Joueur
    {
        return $this->joueur2;
    }

    public function setJoueur2(?Joueur $joueur2): static
    {
        $this->joueur2 = $joueur2;

        return $this;
    }

    public function getGagnant(): ?Joueur
    {
        return $this->gagnant;
    }

    public function setGagnant(?Joueur $gagnant): static
    {
        $this->gagnant = $gagnant;

        return $this;
    }
    public function getPdfLink(): ?string
    {
        return $this->pdfLink;
    }

    public function setPdfLink(string $pdfLink): static
    {
        $this->pdfLink = $pdfLink;

        return $this;
    }
}
