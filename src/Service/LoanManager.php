<?php

namespace App\Service;

use App\Entity\Document;
use App\Repository\LoanRepository;
use Symfony\Component\Security\Core\Security;

class LoanManager
{
    public const LOAN_STATUSES = [
        'AVAILABLE' => 'Disponible',
        'NOT_AVAILABLE' => 'Non disponible',
        'LOANED_BY_USER' => 'Emprunté par vous',
    ];

    public function __construct(private LoanRepository $loanRepository, private Security $security)
    {
    }

    public function documentStatus(Document $document): string
    {
        $lastLoan = $this->loanRepository->findOneBy(['document' => $document], ['loanDate' => 'DESC']);

        if ($lastLoan) {
            if (!$lastLoan->getReturnDate() && $lastLoan->getUser() === $this->security->getUser()) {
                $status = self::LOAN_STATUSES['LOANED_BY_USER'];
            } elseif (!$lastLoan->getReturnDate() && $lastLoan->getUser() !== $this->security->getUser()) {
                $status = self::LOAN_STATUSES['NOT_AVAILABLE'];
            }
        }

        return $status ?? self::LOAN_STATUSES['AVAILABLE'];
    }
}
