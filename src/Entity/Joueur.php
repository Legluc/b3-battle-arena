<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: JoueurRepository::class)]
class Joueur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rencontre:read', 'joueur:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Groups(['rencontre:read', 'joueur:read', 'joueur:write'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide.")]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
    #[Groups(['joueur:read', 'joueur:write'])]
    private ?string $mail = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date de naissance ne peut pas être vide.")]
    #[Assert\LessThanOrEqual("-18 years", message: "Le joueur doit être majeur.")]
    #[Groups(['joueur:read', 'joueur:write'])]
    private ?\DateTimeInterface $ddn = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['joueur:read'])]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, Rencontre>
     */
    #[ORM\OneToMany(targetEntity: Rencontre::class, mappedBy: 'joueur1')]
    private Collection $rencontres;

    public function __construct()
    {
        $this->rencontres = new ArrayCollection();
        $this->roles = ['ROLE_PLAYER'];
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // Nettoyage des données sensibles temporaires
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;
        return $this;
    }

    public function getDdn(): ?\DateTimeInterface
    {
        return $this->ddn;
    }

    public function setDdn(\DateTimeInterface $ddn): static
    {
        $this->ddn = $ddn;
        return $this;
    }

    /**
     * @return Collection<int, Rencontre>
     */
    public function getRencontres(): Collection
    {
        return $this->rencontres;
    }

    public function addRencontre(Rencontre $rencontre): static
    {
        if (!$this->rencontres->contains($rencontre)) {
            $this->rencontres->add($rencontre);
            $rencontre->setJoueur1($this);
        }
        return $this;
    }

    public function removeRencontre(Rencontre $rencontre): static
    {
        if ($this->rencontres->removeElement($rencontre)) {
            if ($rencontre->getJoueur1() === $this) {
                $rencontre->setJoueur1(null);
            }
        }
        return $this;
    }
}
