<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function getTasksList(?array $searchParams = null)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->join('t.project', 'p', 'ON t.project_id = p.id');

        if (!empty($searchParams['word'] ?? null)) {
            $queryBuilder->andWhere('t.name LIKE :word OR t.description LIKE :word OR p.name LIKE :word OR p.description LIKE :word')
                ->setParameter('word', '%' . $searchParams['word'] . '%');
        }

        if (!empty($searchParams['date_from'] ?? null)) {
            $queryBuilder->andWhere('t.created_at >= :date_from')
                ->setParameter('date_from', $searchParams['date_from']);
        }
        if (!empty($searchParams['date_to'] ?? null)) {
            $queryBuilder->andWhere('t.created_at <= :date_to')
                ->setParameter('date_to', $searchParams['date_to']);
        }

        if (is_numeric($searchParams['project_id'] ?? null)) {
            $queryBuilder->andWhere('t.project_id = :project_id')
                ->setParameter('project_id', $searchParams['project_id']);
        }
        if (is_numeric($searchParams['completed'] ?? null)) {
            $queryBuilder->andWhere('t.completed = :completed')
                ->setParameter('completed', $searchParams['completed']);
        }

        return $queryBuilder->getQuery()
            ->getResult();
    }

    public function getTasksTotals()
    {
        return $this->createQueryBuilder('t')
            ->select([
                'COUNT(t.id) as tasks_total',
                'SUM(CASE WHEN t.completed = 1 THEN 1 ELSE 0 END) as tasks_completed'
            ])
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
}
