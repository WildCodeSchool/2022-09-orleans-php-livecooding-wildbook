<?php

namespace App\Controller;

use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{
    #[Route('/document', name: 'app_document')]
    public function index(DocumentRepository $documentRepository): Response
    {
        $documents = $documentRepository->findBy([], ['title' => 'ASC']);
        return $this->render('document/index.html.twig', [
            'documents' => $documents,
        ]);
    }
}
