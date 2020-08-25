<?php
namespace App\DataFixtures;

use App\Entity\Record;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecordFixtures extends BaseFixture implements DependentFixtureInterface
{
    public function loadData()
    {
        $this->createMany(100, 'record', function(){
            return (new Record())
                    ->setTitle($this->faker->catchPhrase)
                    ->setDescription($this->faker->optional()->realText())
                    ->setReleasedAt($this->faker->dateTimeBetween('-2 years'))
                    ->setArtist($this->getRandomReference('artist'))
                    ->setLabel($this->getRandomReference('label'));

        });

    }


    public function getDependencies()
    {
        return [
            ArtistFixtures::class,
            LabelFixtures::class
        ];
    }



}