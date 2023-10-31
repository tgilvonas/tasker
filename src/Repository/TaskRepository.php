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

    public function getTasksList(?int $projectId = null, ?int $userId = null, ?int $completed = null)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->join('t.project', 'p', 'ON t.project_id = p.id');

        if (is_numeric($projectId)) {
            $queryBuilder->andWhere('t.project_id = :project_id')
                ->setParameter('project_id', $projectId);
        }
        if (is_numeric($userId)) {
            //@todo
        }
        if (is_numeric($completed)) {
            $queryBuilder->andWhere('t.completed = :completed')
                ->setParameter('completed', $completed);
        }

        return $queryBuilder->getQuery()
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
