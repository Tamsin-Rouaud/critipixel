<?php

namespace App\Tests\Unit;

use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;

class CalculateAverageRatingTest extends TestCase
{
    public function test_average_is_correct_with_multiple_reviews(): void
    {
        $videoGame = new VideoGame();

        $videoGame->addReview((new Review())->setRating(5));
        $videoGame->addReview((new Review())->setRating(4));
        $videoGame->addReview((new Review())->setRating(3));

        $handler = new RatingHandler();

        $handler->calculateAverage($videoGame);

        $this->assertEquals(4, $videoGame->getAverageRating()); // (5+4+3)/3 = 4
    }

    public function test_average_is_null_with_no_reviews(): void
    {
        $videoGame = new VideoGame();

        $handler = new RatingHandler();

        $handler->calculateAverage($videoGame);

        $this->assertNull($videoGame->getAverageRating()); // aucune note → null
    }

    public function test_average_rounds_up(): void
    {
        $videoGame = new VideoGame();

        $videoGame->addReview((new Review())->setRating(4));
        $videoGame->addReview((new Review())->setRating(5));
        $videoGame->addReview((new Review())->setRating(4));

        $handler = new RatingHandler();

        $handler->calculateAverage($videoGame);

        $this->assertEquals(5, $videoGame->getAverageRating()); // (4+5+4)=13/3=4.33 → arrondi vers le haut = 5
    }
}
