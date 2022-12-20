<?php

namespace App\Controller;

use App\Form\DocumentSearchType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocumentController extends AbstractController
{
    #[Route('/document', name: 'app_document')]
    public function index(Request $request, DocumentRepository $documentRepository): Response
    {
        $form = $this->createForm(DocumentSearchType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $documents = $documentRepository->searchDocument($data['input']);
        } else {
            $documents = $documentRepository->findBy([], ['title' => 'ASC'])    ;
        }

        return $this->renderForm('document/index.html.twig', [
            'documents' => $documents,
            'form' => $form,
        ]);
    }
}
