<?php

namespace App\Controller;

use Application\Service\ContextService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Unicaen\Framework\Application\Application;

class HelloController extends AbstractController
{
    #[Route('/api/hello', name: 'api_hello', methods: ['GET'])]
    public function hello(): JsonResponse
    {
        $container = Application::getInstance()->container();
        $context = $container->get(ContextService::class);

        $msg = $context->getAnnee()->getLibelle();

        return $this->json([
                               'message' => 'bonjour',
                               'welcome' => 'bienvenue en '.$msg.'!',
                               'timestamp' => time()
                           ]);
    }
}