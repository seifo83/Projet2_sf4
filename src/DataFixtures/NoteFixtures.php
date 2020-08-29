<?php

namespace App\DataFixtures;

use App\Entity\Note;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class NoteFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData()
    {
        $this->createMany(1000, 'note', function(){
            return (new Note())
                        ->setValue($this->faker->randomDigit(5))
                        ->setComment($this->faker->unique()->catchPhrase)
                        ->setCreatedAt($this->faker->dateTimeBetween('-2 years'))
                        ->setRecord($this->getRandomReference('record'))
                        ->setUser($this->getRandomReference('user'))
                        ;
        });
    }

    public function getDependencies()
    {
        return [
            RecordFixtures::class,
            UserFixtures::class
        ];
    }

}
