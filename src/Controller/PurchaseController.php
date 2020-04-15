<?php

declare(strict_types=1);

namespace App\Controller;

use App\{
    Entity\Purchase,
    Form\PurchaseType,
    Repository\PurchaseRepository,
};
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * PurchaseController
 *
 * Performs the crud operation of purchases.
 *
 * @Route("/purchase")
 */
final class PurchaseController extends AbstractController
{
    /**
     * @var PurchaseRepository $purchaseRepository The purchase repository
     */
    private PurchaseRepository $purchaseRepository;

    /**
     * @param PurchaseRepository $purchaseRepository The purchase repository
     */
    public function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * Render the list of purchases
     *
     * @Route("/", name="purchaseList", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('purchase/index.html.twig', [
            'purchases' => $this->purchaseRepository->findAll(),
        ]);
    }

    /**
     * Create a new purchase
     *
     * @Route("/new", name="newPurchase", methods={"GET","POST"})
     * @return Response
     */
    public function new(Request $request): Response
    {
        $purchase = new Purchase();
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->purchaseRepository->save($purchase);

            return $this->redirectToRoute('purchaseList');
        }

        return $this->render('purchase/new.html.twig', [
            'purchase' => $purchase,
            'form' => $form->createView(),
        ]);
    }
}
