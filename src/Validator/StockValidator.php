<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\{
    Constraint,
    ConstraintValidator,
};
use App\Repository\{
    PurchaseRepository,
    SaleRepository,
};

/**
 * StockValidator
 *
 * Validate the stock for sale.
 */
final class StockValidator extends ConstraintValidator
{
    /**
     * @var SaleRepository $saleRepository The sale repository
     */

    private SaleRepository $saleRepository;
    /**
     * @var PurchaseRepository $purchaseRepository the purchase repository
     */
    private PurchaseRepository $purchaseRepository;

    /**
     * @param SaleRepository $saleRepository The sale repository
     * @param PurchaseRepository $purchaseRepository the purchase repository
     */
    public function __construct(SaleRepository $saleRepository, PurchaseRepository $purchaseRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * Validate whether the purchase stock is avaiable or not.
     *
     * @param float|null @value The sold quantity
     * @param Constraint $constraint
     * @return void|bool
     */
    public function validate($value, Constraint $constraint)
    {
        $purchasedQuantity = $this->purchaseRepository->totalStock();
        $soldQuantity = $this->saleRepository->totalStock();

        if ($purchasedQuantity >= $soldQuantity + $value) {
            return true;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
