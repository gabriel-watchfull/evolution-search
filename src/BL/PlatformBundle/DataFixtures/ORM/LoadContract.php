<?php


namespace BL\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BL\PlatformBundle\Entity\Contract;

class LoadContract implements FixtureInterface {
    public function load(ObjectManager $manager)
    {
        // Liste des noms de catégorie à ajouter
        $types = array(
            'CDI',
        );

        foreach ($types as $type) {
            // On crée la catégorie
            $contract = new Contract();
            $contract->setType($type);

            // On la persiste
            $manager->persist($contract);
        }

        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
    }
}