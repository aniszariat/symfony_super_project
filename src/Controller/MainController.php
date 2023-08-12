<?php

namespace App\Controller ;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/test')]

    public function test()
    {
        $name='Anis';
        $age=28;
        return $this->render("main.html.twig", [
            'name'=>$name,'age'=>$age
        ]);
    }
}
