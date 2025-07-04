<?php

namespace App\Tests\Unit;

use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;

class CountRatingsPerValueTest extends TestCase
{
    public function test_counts_are_correct(): void
    {
        $videoGame = new VideoGame();

        $videoGame->addReview((new Review())->setRating(5));
        $videoGame->addReview((new Review())->setRating(5));
        $videoGame->addReview((new Review())->setRating(4));
        $videoGame->addReview((new Review())->setRating(3));
        $videoGame->addReview((new Review())->setRating(1));

        $handler = new RatingHandler();
        $handler->countRatingsPerValue($videoGame);

        $counts = $videoGame->getNumberOfRatingsPerValue();

        $this->assertEquals(1, $counts->getNumberOfOne());
        $this->assertEquals(0, $counts->getNumberOfTwo());
        $this->assertEquals(1, $counts->getNumberOfThree());
        $this->assertEquals(1, $counts->getNumberOfFour());
        $this->assertEquals(2, $counts->getNumberOfFive());
    }

    public function test_counts_are_zero_when_no_reviews(): void
    {
        $videoGame = new VideoGame();

        $handler = new RatingHandler();
        $handler->countRatingsPerValue($videoGame);

        $counts = $videoGame->getNumberOfRatingsPerValue();

        $this->assertEquals(0, $counts->getNumberOfOne());
        $this->assertEquals(0, $counts->getNumberOfTwo());
        $this->assertEquals(0, $counts->getNumberOfThree());
        $this->assertEquals(0, $counts->getNumberOfFour());
        $this->assertEquals(0, $counts->getNumberOfFive());
    }
}
