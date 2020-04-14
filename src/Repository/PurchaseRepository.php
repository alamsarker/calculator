<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PurchaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    public function totalStock(): int
    {
        return (int) $this
            ->getEntityManager()
            ->getConnection()
            ->fetchColumn("SELECT COALESCE(SUM(quantity),0) FROM purchase")
            ;
    }

    public function save(Purchase $purchase): void
    {
        $this->getEntityManager()->persist($purchase);
        $this->getEntityManager()->flush();
    }
}
