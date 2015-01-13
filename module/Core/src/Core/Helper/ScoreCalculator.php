<?php

namespace Core\Helper;

class ScoreCalculator
{
    public static function updateScore($solvedPercentage, $difficulty, $numTries)
    {
        $solvedScore = 1.0 * $solvedPercentage * $difficulty;
        return $solvedScore / pow(1.0 * $numTries, 1.0 / 5.0);
    }
}
