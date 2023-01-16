<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Document;
use App\Entity\DocumentSearch;
use App\Entity\Loan;
use App\Entity\User;
use App\Form\DocumentSearchType;
use App\Repository\DocumentRepository;
use App\Repository\LoanRepository;
use App\Service\LoanManager;
use DateTime;
use Exception;
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

    #[Route('/document/loan/{document}', name: 'app_document_loan', methods: ['POST'])]
    public function loan(
        Request $request,
        Document $document,
        LoanRepository $loanRepository,
        LoanManager $loanManager
    ): Response {
        if ($this->isCsrfTokenValid('loan' . $document->getId(), $request->request->get('_token'))) {
            if (!$loanManager->isAvailable($document)) {
                throw new Exception('Impossible');
            }

            $loan = new Loan();
            $loan->setDocument($document);

            /** @var User */
            $user = $this->getUser();
            $loan->setUser($user);
            $loan->setLoanDate(new DateTime());
            $loanRepository->save($loan, true);

            $this->addFlash('success', 'Le document a bien été emprunté');
        }

        return $this->redirectToRoute('app_document_show', [
            'document' => $document->getId()
        ], Response::HTTP_SEE_OTHER);
    }


    #[Route('/document/{document}', name: 'app_document_show')]
    public function show(Document $document, LoanManager $loanManager): Response
    {
        return $this->renderForm('document/show.html.twig', [
            'document' => $document,
            'status' => $loanManager->documentStatus($document),
            'is_available' => $loanManager->isAvailable($document)
        ]);
    }
}
