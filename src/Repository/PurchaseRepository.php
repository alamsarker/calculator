<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * PurchaseRepository
 *
 * Handle all query related task of purchase.
 */
class PurchaseRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    /**
     * Get the total number of quantity of all purchase.
     *
     * @return int Total number of quantities
     */
    public function totalStock(): int
    {
        return (int) $this
            ->getEntityManager()
            ->getConnection()
            ->fetchColumn("SELECT COALESCE(SUM(quantity),0) FROM purchase")
            ;
    }

    /**
     * Save a new purchase
     *
     * @param Purchase $purchase The purchase object
     * @return void
     */
    public function save(Purchase $purchase): void
    {
        $this->getEntityManager()->persist($purchase);
        $this->getEntityManager()->flush();
    }
}
