<?php

namespace App\Repository;

use App\Entity\Resultat;
use App\Entity\Joueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Resultat>
 */
class ResultatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Resultat::class);
    }

    public function findByJoueur(Joueur $joueur)
    {
        return $this->createQueryBuilder('r')
            ->join('r.rencontre', 'rencontre') 
            ->join('rencontre.joueur1', 'j1')
            ->join('rencontre.joueur2', 'j2')
            ->where('j1 = :joueur OR j2 = :joueur')
            ->setParameter('joueur', $joueur)
            ->getQuery()
            ->getResult();
    }

}
