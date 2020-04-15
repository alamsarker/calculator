<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * SaleRepository
 *
 * Handle all query related task of sale.
 */
class SaleRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    /**
     * Get the total number of quantity of all sales.
     *
     * @return int Total number of quantity
     */
    public function totalStock(): int
    {
        return (int) $this
            ->getEntityManager()
            ->getConnection()
            ->fetchColumn("SELECT COALESCE(SUM(quantity),0) FROM sales")
            ;
    }

    /**
     * Get the total profit from the given sequences of purchases and sales.
     *
     * @return float Total Profit
     */
    public function getProfit(): float
    {
        $sql = "SELECT
                COALESCE(S.totalSold - P.totalCost,0) as profit
            FROM (
                SELECT
                    SUM(quantity * price) as totalSold,
                    @totalQnty := SUM(quantity) as totalQnty,
                    1 as id
                FROM sales
            ) as S
            LEFT JOIN (
                SELECT
                    SUM(price * LEAST(@totalQnty, quantity)) as totalCost ,
                    SUM( @totalQnty := @totalQnty - LEAST(@totalQnty, quantity)),
                    1 as id
                FROM purchase where @totalQnty > 0 ORDER BY id asc
            ) as P ON S.id = P.id
        ";

        return (float) $this
            ->getEntityManager()
            ->getConnection()
            ->fetchColumn($sql)
            ;
    }

    /**
     * Save a new sale
     *
     * @param Sale $sale The Sale object
     * @return void
     */
    public function save(Sale $sale): void
    {
        $this->getEntityManager()->persist($sale);
        $this->getEntityManager()->flush();
    }
}
