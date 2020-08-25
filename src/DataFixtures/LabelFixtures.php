<?php

namespace App\DataFixtures;

use App\Entity\Label;

class LabelFixtures extends BaseFixture
{
    public function loadData()
    {
        //créer 10 label(maison de disuqe)
        $this->createMany(10, 'label', function(){
            return (new Label())
                ->setName($this->faker->name)
                ->setDescription($this->faker->realText())
                ->setReleasedAt($this->faker->dateTimeBetween('-10 years'))
                ;
        });
    }
}
