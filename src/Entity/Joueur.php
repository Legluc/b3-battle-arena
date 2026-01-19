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
    #[ORM\OneToMany(mappedBy: 'joueur1', targetEntity: Rencontre::class)]
    private Collection $rencontresJoueur1;

    #[ORM\OneToMany(mappedBy: 'joueur2', targetEntity: Rencontre::class)]
    private Collection $rencontresJoueur2;

    public function __construct()
    {
        $this->rencontresJoueur1 = new ArrayCollection();
        $this->rencontresJoueur2 = new ArrayCollection();
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
    public function getRencontresJoueur1(): Collection
    {
        return $this->rencontresJoueur1;
    }

    /**
     * @return Collection<int, Rencontre>
     */
    public function getRencontresJoueur2(): Collection
    {
        return $this->rencontresJoueur2;
    }

    /**
     * Retourne toutes les rencontres (en tant que joueur1 ou joueur2)
     * @return Collection<int, Rencontre>
     */
    public function getAllRencontres(): Collection
    {
        return new ArrayCollection(array_merge(
            $this->rencontresJoueur1->toArray(),
            $this->rencontresJoueur2->toArray()
        ));
    }

    public function addRencontreJoueur1(Rencontre $rencontre): static
    {
        if (!$this->rencontresJoueur1->contains($rencontre)) {
            $this->rencontresJoueur1->add($rencontre);
            $rencontre->setJoueur1($this);
        }
        return $this;
    }

    public function addRencontreJoueur2(Rencontre $rencontre): static
    {
        if (!$this->rencontresJoueur2->contains($rencontre)) {
            $this->rencontresJoueur2->add($rencontre);
            $rencontre->setJoueur2($this);
        }
        return $this;
    }

    public function removeRencontreJoueur1(Rencontre $rencontre): static
    {
        if ($this->rencontresJoueur1->removeElement($rencontre)) {
            if ($rencontre->getJoueur1() === $this) {
                $rencontre->setJoueur1(null);
            }
        }
        return $this;
    }

    public function removeRencontreJoueur2(Rencontre $rencontre): static
    {
        if ($this->rencontresJoueur2->removeElement($rencontre)) {
            if ($rencontre->getJoueur2() === $this) {
                $rencontre->setJoueur2(null);
            }
        }
        return $this;
    }
}
