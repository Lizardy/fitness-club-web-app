<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Trainer;

class LoadTrainerData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $trainer1 = new Trainer();
        $trainer1->setLastName('Смирнов');
        $trainer1->setFirstName('Виталий');
        $trainer1->setPatronym('Валерьевич');
        $manager->persist($trainer1);
        $manager->flush();
        $this->addReference('trainer1', $trainer1);

        $trainer2 = new Trainer();
        $trainer2->setLastName('Петухова');
        $trainer2->setFirstName('Анастасия');
        $trainer2->setPatronym('Евгеньевна');
        $manager->persist($trainer2);
        $manager->flush();
        $this->addReference('trainer2', $trainer2);
    }

    public function getOrder()
    {
        return 1;
    }
}