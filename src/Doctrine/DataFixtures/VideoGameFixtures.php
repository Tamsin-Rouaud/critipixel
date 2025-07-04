<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Tag;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use App\Model\Entity\Review;
use App\Rating\CalculateAverageRating;
use App\Rating\CountRatingsPerValue;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

final class VideoGameFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly Generator $faker,
        private readonly CalculateAverageRating $calculateAverageRating,
        private readonly CountRatingsPerValue $countRatingsPerValue
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Récupération des utilisateurs et des tags existants
        $users = $manager->getRepository(User::class)->findAll();
        $allTags = $manager->getRepository(Tag::class)->findAll();

        $videoGames = [];

        // Création de 50 jeux vidéo
        for ($i = 0; $i < 50; $i++) {
            $game = (new VideoGame())
                ->setTitle(sprintf('Jeu vidéo %d', $i))
                ->setDescription($this->faker->paragraphs(10, true))
                ->setReleaseDate(new DateTimeImmutable())
                ->setTest($this->faker->paragraphs(6, true))
                ->setRating(($i % 5) + 1)
                ->setImageName(sprintf('video_game_%d.png', $i))
                ->setImageSize(2_098_872);

            // Associer 1 à 3 tags aléatoires
            $randomTags = $this->faker->randomElements($allTags, random_int(1, 3));
            foreach ($randomTags as $tag) {
                $game->addTag($tag);
            }

            $videoGames[] = $game;
            $manager->persist($game);
        }

        // Création des reviews pour chaque jeu
        foreach ($videoGames as $game) {
            $reviewCount = random_int(2, 5);

            for ($j = 0; $j < $reviewCount; $j++) {
                $review = (new Review())
                    ->setRating(random_int(1, 5))
                    ->setComment($this->faker->optional(0.6)->sentence(10))
                    ->setUser($this->faker->randomElement($users))
                    ->setVideoGame($game);

                $manager->persist($review);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TagFixtures::class,
        ];
    }
}
