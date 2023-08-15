<?php

namespace App\Controller ;

use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/test', name:'app_test')]

    public function test()
    {
        $name='Anis';
        $age=28;
        return $this->render("main.html.twig", [
            'name'=>$name,'age'=>$age
        ]);
    }
    #[Route('/contact/{id}', name:'app_contact', condition: "params['id']<100")]
    public function contact($id)
    {
        return $this->render("contact.html.twig", ['id'=>$id]);
    }
}
