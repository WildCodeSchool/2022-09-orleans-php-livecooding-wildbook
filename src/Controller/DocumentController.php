<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Document;
use App\Entity\DocumentSearch;
use App\Form\DocumentSearchType;
use App\Repository\DocumentRepository;
use App\Service\LoanManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class DocumentController extends AbstractController
{
    #[Route('/document', name: 'app_document')]
    public function index(Request $request, DocumentRepository $documentRepository): Response
    {
        $documentSearch = new DocumentSearch();
        $form = $this->createForm(DocumentSearchType::class, $documentSearch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = '';
            if ($documentSearch->getCategory() instanceof Category) {
                $category = $documentSearch->getCategory()->getName();
            }

            $documents = $documentRepository->searchDocument($documentSearch->getInput(), $category);
        } else {
            $documents = $documentRepository->findBy([], ['title' => 'ASC']);
        }

        return $this->renderForm('document/index.html.twig', [
            'documents' => $documents,
            'form' => $form,
        ]);
    }

    #[Route('/document/{document}', name: 'app_document_show')]
    public function show(Document $document, LoanManager $loanManager): Response
    {
        return $this->renderForm('document/show.html.twig', [
            'document' => $document,
            'status' => $loanManager->documentStatus($document)
        ]);
    }
}
