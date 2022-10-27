<?php

namespace App\Repository;

use App\Entity\FiltreSorties;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function add(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param FiltreSorties $filtreSorties
     * @return Paginator
     */
    public function findFiltreSorties(FiltreSorties $filtreSorties, Participant $participant): Paginator
    {
        dump($filtreSorties);
        $qBuilder = $this->createQueryBuilder('s');
        $qBuilder
            ->select('s')
            ->join('s.participantSortie', 'partSort');
        if($filtreSorties->getCampusFiltre() != null){
            $qBuilder = $qBuilder
                ->andWhere('s.siteOrganisateur = :campusFiltre')
                ->setParameter('campusFiltre', $filtreSorties->getCampusFiltre()->getId());
        }
        if($filtreSorties->getMotCle() != null){
            $qBuilder = $qBuilder
                //(:search BOOLEAN) > 0
                ->andWhere('s.nom LIKE :motCle')
                ->setParameter('motCle', "%{$filtreSorties->getMotCle()}%");
        }
        if($filtreSorties->getDateDebutRech() != null && $filtreSorties->getDateFinRech() != null){
            $qBuilder = $qBuilder
                ->andWhere('s.dateHeureDebut >= :dateDebutRech')
                ->setParameter('dateDebutRech', $filtreSorties->getDateDebutRech())
                ->andWhere('s.dateHeureDebut <= :dateFinRech')
                ->setParameter('dateFinRech', $filtreSorties->getDateFinRech());
        }
        if($filtreSorties->getOrganisateurSortie() == 1){
            $qBuilder = $qBuilder
                ->andWhere('s.organisateur = :idUser')
                ->setParameter('idUser', $participant->getId());

        }
        if($filtreSorties->getInscrit() == 1) {
            $qBuilder = $qBuilder
                ->andWhere('partSort = :idUser')
                ->setParameter('idUser', $participant->getId());
        }
        if($filtreSorties->getNonInscrit() == 1){
            $qBuilder = $qBuilder
                ->andWhere('partSort <> :idUser')
                ->setParameter('idUser', $participant->getId());
        }
        if($filtreSorties->getEtatFiltre() == 1){
            $qBuilder = $qBuilder
                ->andWhere('s.etat = :etatFiltre')
                ->setParameter('etatFiltre', 4);
        }
        $query = $qBuilder->getQuery();
        //$sorties = $query->getResult();
        //dump($sorties);
        $paginator = new Paginator($query);
        return $paginator;
    }


//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
