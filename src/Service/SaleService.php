<?php

namespace App\Service;
 
use App\Repository\SaleRepository;
use App\Repository\PurchaseRepository;


class SaleService
{
    protected $saleRepository;
    protected $purchaseRepository;

    public function __construct( SaleRepository $saleRepository, PurchaseRepository $purchaseRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->purchaseRepository = $purchaseRepository;
    }

    public function calculateProfit()
    {
        $purchases = $this->purchaseRepository->getAvailStock();

        dd($purchases);
    }

    public function save($sale)
    {
        //$profit = $this->calculateProfit();
        //$sale->setProfit();
        $this->saleRepository->save($sale);
    }

    
}
