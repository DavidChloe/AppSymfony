<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


class TestController extends AbstractController 
{ 
    #[Route('/test', name: 'app_test',methods: ['GET', 'HEAD'] )] 
    public function index(Request $request): Response 
    { 
 $session = $request->getSession(); 
        $session->getFlashBag()->add('message', 'message informatif'); 
        $session->getFlashBag()->add('message', 'message complémentaire'); 
        $session->set('statut', 'primary'); 
 
        return $this->render('test/index.html.twig'); 
    } 
 
    #[Route('/redirection', name: 'redirection')] 
    public function redirection(Request $request)  
    {  
        // récupération de la session  
        $session = $request->getSession(); // session_start  
        $nomUser = $session->get('nom_user');  
 
        return new Response("ici redirection: variable nom de 
                session: $nomUser");  
    }  

    #[Route('/hello/{nom}/{prenom}', name: 'helloName', defaults: ['prenom' => ''])]
    public function helloName($nom, $prenom)
    {
        return new Response("Hello $prenom $nom !");
    }
    
    #[Route('/hello/{age}/{nom}/{prenom}', name: 'hello', requirements: ["nom" => "[a-z]{2,50}"])]
    public function hello(Request $request, int $age, $nom, $prenom='') 
    {
        return $this->render('test/hello.html.twig', [ 
            'nom' => $nom, 
            'prenom' => $prenom, 
          'age' => $age, 
          'messageHtml'=>'<h3>je vais tester raw</h3>', 
          'monTableau'=> [ 'profession'=>'formateur',  
                           'sexe'=>'M',  
                           'specialité'=>'Symfony'] 
        ]); 
    }
    


    
    

}