<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\PurchaseType;
use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/purchase")
 */
final class PurchaseController extends AbstractController
{
    private PurchaseRepository $purchaseRepository;

    public function __construct(PurchaseRepository $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * @Route("/", name="purchaseList", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('purchase/index.html.twig', [
            'purchases' => $this->purchaseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="newPurchase", methods={"GET","POST"})
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
