<?php

namespace App\DataFixtures;

use App\Entity\Loan;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoanFixtures extends Fixture implements DependentFixtureInterface
{
    public const DOCUMENT_NUMBER = 10;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < DocumentFixtures::DOCUMENT_NUMBER; $i++) {
            $loan = new Loan();
            $loan->setDocument($this->getReference('document' . $i));
            $loan->setUser($this->getReference('user'));
            $loan->setLoanDate(new DateTime('2022-01-01'));
            $loan->setReturnDate(new DateTime('2022-02-01'));
            $manager->persist($loan);
        }

        // emprunter par moi et non rendu
        $loan = new Loan();
        $loan->setDocument($this->getReference('document0'));
        $loan->setUser($this->getReference('admin'));
        $loan->setLoanDate(new DateTime('2023-01-01'));
        $manager->persist($loan);

        // emprunter par qq'un d'autre et non rendu
        $loan = new Loan();
        $loan->setDocument($this->getReference('document1'));
        $loan->setUser($this->getReference('user'));
        $loan->setLoanDate(new DateTime('2023-01-01'));
        $manager->persist($loan);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DocumentFixtures::class,
            UserFixtures::class,
        ];
    }
}
