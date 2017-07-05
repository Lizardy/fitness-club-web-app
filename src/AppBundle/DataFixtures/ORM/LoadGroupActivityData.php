<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\GroupActivity;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadGroupActivityData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $activity1 = new GroupActivity();
        $activity1->setActivityName('Пилатес');
        $activity1->setDescription('Пилатес');
        $activity1->setTrainer($this->getReference('trainer2'));
        $manager->persist($activity1);
        $manager->flush();

        $activity2 = new GroupActivity();
        $activity2->setActivityName('Кроссфит');
        $activity2->setDescription('Кроссфит');
        $activity2->setTrainer($this->getReference('trainer1'));
        $manager->persist($activity2);
        $manager->flush();

        $activity3 = new GroupActivity();
        $activity3->setActivityName('Хатха йога');
        $activity3->setDescription('Хатха йога');
        $activity3->setTrainer($this->getReference('trainer2'));
        $manager->persist($activity3);
        $manager->flush();

        $activity4 = new GroupActivity();
        $activity4->setActivityName('Кардио');
        $activity4->setDescription('Кардио');
        $activity4->setTrainer($this->getReference('trainer1'));
        $manager->persist($activity4);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}