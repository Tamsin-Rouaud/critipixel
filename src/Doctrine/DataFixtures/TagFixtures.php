<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Liste de tags réalistes, sans logique purement incrémentale
        $tagNames = [
            'Action',
            'Aventure',
            'RPG',
            'Stratégie',
            'Simulation',
            'Horreur',
            'Puzzle',
            'Course',
            'Combat',
            'Plateforme',
            'Multijoueur',
            'Coopératif',
            'Solo',
            'FPS',
            'TPS',
            'Rétro',
            'Indépendant',
            'Sandbox',
            'Narratif',
            'Open World',
            'Pixel Art',
            'Roguelike',
            'Survie',
            'Construction',
            'Musique'
        ];

        foreach ($tagNames as $name) {
            $tag = (new Tag())->setName($name);
            // Le champ "code" sera généré automatiquement grâce à #[Slug(fields: ['name'])]
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
