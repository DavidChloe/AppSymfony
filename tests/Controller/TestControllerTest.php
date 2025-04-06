<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


final class TestControllerTest extends WebTestCase
{
     #[Route('/test', name: 'app_test')]
    public function index(Request $request): Response
    {
        $info = $request->query->get('info');
        $allParams = $request->query->all();
        
        return $this->render('test/index.html.twig', [
            'info' => $info,
            'allParams' => $allParams,
        ]);
    }

}
