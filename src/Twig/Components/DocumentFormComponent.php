<?php

namespace App\Twig\Components;

use App\Entity\Document;
use App\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('document_form')]
final class DocumentFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    protected function instantiateForm(): FormInterface
    {
        $document = new Document();
        return $this->createForm(DocumentType::class, $document);
    }
}
