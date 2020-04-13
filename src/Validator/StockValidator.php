<?php declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\PurchaseRepository;
use App\Repository\SaleRepository;


final class StockValidator extends ConstraintValidator
{
    private SaleRepository $saleRepository;
    private PurchaseRepository $purchaseRepository;

    public function __construct(SaleRepository $saleRepository, PurchaseRepository $purchaseRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->purchaseRepository = $purchaseRepository;
    }

    public function validate($value, Constraint $constraint)
    {               
        $purchasedQuantity = $this->purchaseRepository->totalStock();
        $soldQuantity = $this->saleRepository->totalStock();

        if( $purchasedQuantity >= $soldQuantity + $value ) {
            return true;
        }
        
        $this->context->buildViolation($constraint->message)            
            ->addViolation();
    }
}
