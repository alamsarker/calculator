<?php

declare(strict_types=1);

namespace App\Controller;

use App\{
    Entity\Sale,
    Form\SaleType,
    Repository\SaleRepository,
};
use Symfony\Component\HttpFoundation\{Request,Response};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * SaleController
 *
 * Performs the crud operation of sales.
 *
 * @Route("/sales")
 */
final class SaleController extends AbstractController
{
    /**
     * @var SaleRepository $saleRepository The sale repository.
     */
    private SaleRepository $saleRepository;

    /**
     * @param SaleRepository $saleRepository The sale repository.
     */
    public function __construct(SaleRepository $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    /**
     * Render the list of sales
     *
     * @Route("/", name="salesList", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('sale/index.html.twig', [
            'sales' => $this->saleRepository->findAll(),
        ]);
    }

    /**
     * Render the profit of all sold items.
     *
     * @Route("/profit", name="profit", methods={"GET"})
     * @return Response
     */
    public function profit(): Response
    {
        return $this->render('sale/profit.html.twig', [
            'profit' => $this->saleRepository->getProfit(),
        ]);
    }

    /**
     * Create a new sale.
     *
     * @Route("/new", name="newSale", methods={"GET","POST"})
     * @return Response
     */
    public function new(Request $request): Response
    {
        $sale = new Sale();
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saleRepository->save($sale);

            return $this->redirectToRoute('salesList');
        }

        return $this->render('sale/new.html.twig', [
            'sale' => $sale,
            'form' => $form->createView(),
        ]);
    }
}
