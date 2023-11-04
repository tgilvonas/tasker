<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getProjectsList(array $paramsCountTotals = []): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $select = ['p.id', 'p.name'];

        if ($paramsCountTotals['total'] ?? false) {
            $select[] = 'COUNT(t.id) as tasks_total';
        }
        if ($paramsCountTotals['completed'] ?? false) {
            $select[] = 'SUM(CASE WHEN t.completed = 1 THEN 1 ELSE 0 END) as tasks_completed';
        }
        if ($paramsCountTotals['uncompleted'] ?? false) {
            $select[] = 'SUM(CASE WHEN t.completed = 0 THEN 1 ELSE 0 END) as tasks_uncompleted';
        }

        return $queryBuilder->select($select)
            ->leftJoin('p.tasks', 't')
            ->orderBy('p.ord', 'ASC')
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Project[] Returns an array of Project objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
}
