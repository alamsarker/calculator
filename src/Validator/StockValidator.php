<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\PurchaseRepository;
use App\Repository\SaleRepository;


class StockValidator extends ConstraintValidator
{
    private $saleRepository;
    private $purchaseRepository;
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
