<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sale;
use App\Form\SaleType;
use App\Repository\SaleRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sales")
 */
final class SaleController extends AbstractController
{
    private SaleRepository $saleRepository;

    public function __construct(SaleRepository $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    /**
     * @Route("/", name="salesList", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('sale/index.html.twig', [
            'sales' => $this->saleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/profit", name="profit", methods={"GET"})
     */
    public function profit(): Response
    {
        return $this->render('sale/profit.html.twig', [
            'profit' => $this->saleRepository->getProfit(),
        ]);
    }

    /**
     * @Route("/new", name="newSale", methods={"GET","POST"})
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
