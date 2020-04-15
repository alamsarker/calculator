<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * HomeController
 *
 * Show the landing page of the calculator.
 */
final class HomeController extends AbstractController
{
    /**
     * Render the landing page of the task
     *
     * @Route("/", name="home")
     * @return Response
     */
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }
}
