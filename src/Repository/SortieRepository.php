<?php

namespace App\Repository;

use App\Entity\FiltreSorties;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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
    public function findFiltreSorties(FiltreSorties $filtreSorties): Paginator
    {
        $qBuilder = $this->createQueryBuilder('s');
        $qBuilder
            ->select('s');
        if($filtreSorties->getCampusFiltre() != null){
            dump($filtreSorties->getCampusFiltre());
            $qBuilder = $qBuilder
                ->andWhere('s.siteOrganisateur = :campusFiltre')
                ->setParameter('campusFiltre', $filtreSorties->getCampusFiltre()->getId());
        }
        if($filtreSorties->getMotCle() != null){
            dump($filtreSorties->getMotCle());
            $qBuilder = $qBuilder
                ->andWhere('MATCH_AGAINST(s.nom) AGAINST (:motCle boolean) >0 ')
                ->setParameter('motCle', $filtreSorties->getMotCle());
        }
        if(!empty($filtreSorties->getDateDebutRech()) && ($filtreSorties->getDateFinRech())){
            $qBuilder = $qBuilder
                ->andWhere('s.dateHeureDebut LIKE :dateDebutRech AND s.dateHeureDebut LIKE :dateDebutRech')
                ->setParameter('dateDebutRech', $filtreSorties->getDateDebutRech())
                ->setParameter('dateFinRech', $filtreSorties->getDateFinRech());;
        }
        if(!empty($filtreSorties->getOrganisateurSortie())){
            $qBuilder = $qBuilder
                ->andWhere('s.campusId LIKE :campusFiltre')
                ->setParameter();

        }
        if(!empty($filtreSorties->getInscrit())){
            $qBuilder = $qBuilder
                ->andWhere('s.campusId LIKE :campusFiltre')
            ->setParameter();
        }
        if(!empty($filtreSorties->getNonInscrit())){
            $qBuilder = $qBuilder
                ->andWhere('s.campusId LIKE :campusFiltre')
                ->setParameter();
        }
        if(!empty($filtreSorties->getEtatFiltre())){
            $qBuilder = $qBuilder
                ->andWhere('s.campusId LIKE :campusFiltre')
                ->setParameter();
        }
        dump($qBuilder);
        $query = $qBuilder->getQuery();
        dump($query);
        $paginator = new Paginator($query);
        dump($paginator);

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
