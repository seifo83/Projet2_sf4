<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * classe "modéle" pour les fixtures
 * On ne peut pas instancier une abstraction
 */
abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager*/
    private $manager;

    /** @var Generator */
    protected $faker;

    /**
     * Méthode à implémenter par les classes qui héritent
     * pour générer les fausses données
     */


     abstract protected function loadData();

     /**
      * Méthode appelée par le systéme systéme  de fixtures
      */

      public function load(ObjectManager $manager)
      {

        //on enregistre le ObjectManager
        $this->manager = $manager;
        //on  instancie Faker
        $this->faker = Factory::create('fr_FR');


        //on appel loadData() pour avoir les fausses données
        $this->loadData();
        //on éxecute l'enregistrement en base
        $this->manager->flush();
    
    }




    /**
     * Enregistrer plusieurs entités 
     * @param init $count                      nombre d'entitéa générer 
     * @param callable $factory             fonction qui génére 1 entité
     */
    protected function createMany($count, $factory)
    {
        for ($i=0; $i < $count; $i++) { 
            //on execute $factory qui doit retourner l'entité générée
            $entity = $factory();


            //vérifier que i'entité ait été retournée
            if($entity === null){
                throw new \LogicException('L\'entité doit étre retournée. Auriez-vous oublié un "retour"?');
                
            }


            //On prépare à l'enregistrement de l'entité
            $this->manager->persist($entity);


        }
    }


}