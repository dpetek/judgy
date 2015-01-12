<?php

namespace Core\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\MappedSuperclass
 */
abstract class BaseRatable extends Base
{
    /**
     * @ODM\Increment(name="rating")
     */
    protected $rating;

    /**
     * @ODM\Increment(name="numVotes")
     */
    protected $numVotes;

    /**
     * @ODM\Float(name="totalRating")
     */
    protected $totalRating;

    public function addVote($value)
    {
        $this->totalRating += $value;
        $this->numVotes ++;
        $this->updateRating();
    }

    public function changeVote($oldValue, $newValue)
    {
        $this->totalRating = $this->totalRating - $oldValue + $newValue;
        $this->updateRating();
    }

    public function updateRating()
    {
        $this->setRating(1.0 * $this->totalRating / $this->numVotes);
    }

    /**
     * @param mixed $numVotes
     */
    public function setNumVotes($numVotes)
    {
        $this->numVotes = $numVotes;
    }

    /**
     * @return mixed
     */
    public function getNumVotes()
    {
        return $this->numVotes;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return round($this->rating, 1);
    }

    /**
     * @param mixed $totalRating
     */
    public function setTotalRating($totalRating)
    {
        $this->totalRating = $totalRating;
    }

    /**
     * @return mixed
     */
    public function getTotalRating()
    {
        return $this->totalRating;
    }
}
