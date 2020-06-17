<?php

namespace App\Repository;

use App\Entity\Statistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Statistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statistic[]    findAll()
 * @method Statistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistic::class);
    }

    public function updateUserAgent($data)
    {
        $qb = $this->createQueryBuilder('a');
        return $qb->update()->set('browser', $data[1])->set('os', $data[2])->where('ip = :ip')
            ->setParameter('ip', $data[0])->getQuery()->execute();
    }

    public function getItems($limit = 50, $offset = 0, $ipQuery = false)
    {
        $qb = $this->createQueryBuilder('a')->select('a.ip, a.browser, a.os, a.url_from, a.url_to');
        $qb->addSelect('(SELECT COUNT(1) FROM ' . Statistic::class . ' b WHERE b.ip=a.ip GROUP BY b.url_from, b.url_to) AS url_total')
            ->groupBy('a.ip, a.browser, a.os, a.url_from, a.url_to');
        if ($ipQuery) {
            $qb->where('a.ip = :ip')->setParameter('ip', $ipQuery);
        }
        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);
        return [
            'data' => $qb->getQuery()->getResult(),
            'total' => $this->getTotal($ipQuery)
        ];
    }

    public function getTotal($ipQuery = false)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(DISTINCT a.ip)');
        if ($ipQuery) {
            $qb->where('a.ip = :ip')->setParameter('ip', $ipQuery);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }
}
