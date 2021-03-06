<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Film $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Film $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findFilm2000()
    {
        $em = $this->getEntityManager();
        $dql = "
            SELECT f FROM App\Entity\Film f
            WHERE f.annee = 2000
            ORDER BY f.titre DESC
        ";
        $req = $em->createQuery($dql);
        return $req->getResult();
    }

    public function findFilmAvecActeurs()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->leftJoin('f.acteurs', 'a')
            ->addSelect('a');
        $req = $qb->getQuery();
        $req->setMaxResults(2);

        $paginator = new Paginator($req);

        // return $req->getResult();
        return $paginator;
    }


    public function findFilm2000qb()
    {
        // Avec Query Builder
        $qb = $this->createQueryBuilder('f');
        $qb->andWhere('f.annee = 2000');
        $qb->addOrderBy('f.titre', 'DESC');
        $req = $qb->getQuery();
        $resultat = $req->getResult();
        return $resultat;
        /*
        $qb2 = ($this->createQueryBuilder('f'))
            ->andWhere('f.annee = 2000')
            ->addOrderBy('f.titre', 'DESC');
        return $qb2->getQuery()->getResult();
        */
    }

    // /**
    //  * @return Film[] Returns an array of Film objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
