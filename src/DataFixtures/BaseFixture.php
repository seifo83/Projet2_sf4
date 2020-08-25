<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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

    /** @var array liste des références connues (cf.memoisation) */
    private $references = [];

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
     * @param init $count                      nombre d'entité générer 
     * @param string $groupName             nom du groupe de références
     * @param callable $factory             fonction qui génére 1 entité
     */
    protected function createMany($count, $groupName ,$factory)
    {
        for ($i=0; $i < $count; $i++) { 
            //on execute $factory qui doit retourner l'entité générée
            $entity = $factory($i);


            //vérifier que i'entité ait été retournée
            if($entity === null){
                throw new \LogicException('L\'entité doit étre retournée. Auriez-vous oublié un "retour"?');
                
            }


            //On prépare à l'enregistrement de l'entité
            $this->manager->persist($entity);

            //Enregistre une référence à l'entité
            $reference = sprintf('%s_%d', $groupName, $i);
            $this->addReference($reference, $entity);



        }
    }



    /**
     * Récuprer 1 entité par son nom de groupe de références
     * @param string $groupName                     nom de groupe de référence
     */
    protected function getRandomReference($groupName)
    {
        //Vérifier si on a déja enregistré les références du groupe demandé
        if (!isset($this->references[$groupName])){
            //si non on va rechercher les références
            $this->references[$groupName] = [];

            //on parcourt la liste de toutes les références (toutes classes confondues)
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                    //La clé $kay coreespond à nos références
                    //Si $key commence par $groupName, on le sauvgarde
                    if(strpos($key, $groupName) === 0){
                        $this->references[$groupName][] = $key;
                    }
            }
        }

        // Vérifier que l'on a récupérer de références
        if($this->references[$groupName] === []){
            throw new \Exception(sprintf('Aucune référence trouvée pour le groupe "%s"', $groupName));
        }

        //retourner une entité correspondant à une référence aléatoir
        $randomReference = $this->faker->randomElement($this->references[$groupName]);
        return $this->getReference($randomReference);

    }

}