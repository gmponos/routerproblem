<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyOtherController extends AbstractController
{
    /**
     * @Route("/my/other", name="my_other")
     */
    public function index()
    {
        return $this->render('my_other/index.html.twig', [
            'controller_name' => 'MyOtherController',
        ]);
    }
}
