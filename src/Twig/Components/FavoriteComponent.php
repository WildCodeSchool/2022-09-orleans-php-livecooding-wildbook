<?php

namespace App\Twig\Components;

use App\Entity\Document;
use App\Entity\User;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('favorite')]
final class FavoriteComponent extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public bool $favorite = false;

    #[LiveProp(writable: true)]
    public ?Document $document = null;

    public function __construct(private DocumentRepository $documentRepository)
    {
    }

    #[LiveAction]
    public function favorize(): void
    {
        /** @var User */
        $user = $this->getUser();

        if ($this->isFavorite()) {
            $this->document->removeUser($user);
        } else {
            $this->document->addUser($user);
        }
        $this->documentRepository->save($this->document, true);
    }

    public function isFavorite(): bool
    {
        return $this->document->getUsers()->contains($this->getUser());
    }
}
