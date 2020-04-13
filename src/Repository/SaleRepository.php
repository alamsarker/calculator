<?php  declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function totalStock(): int
    {
        return (int) $this
            ->getEntityManager()
            ->getConnection()
            ->fetchColumn("SELECT COALESCE(SUM(quantity),0) FROM sales")
            ;       
    }

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

    public function save(Sale $sale): void
    {       
        $this->getEntityManager()->persist($sale);
        $this->getEntityManager()->flush();
    }
}
