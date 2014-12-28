<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Api\ApiInterface\IResponse;
use Core\Document\Base;

/**
 * @ODM\MappedSuperclass
 */
class BaseMiscProblem extends Base implements IResponse
{
    /**
     * @ODM\String(name="title")
     */
    protected $title;

    /**
     * @ODM\String(name="description")
     */
    protected $description;

    /**
     * @ODM\Int(name="type")
     */
    protected $type;

    /**
     * @ODM\ReferenceMany(name="tags",targetDocument="Judge\Document\Tag")
     */
    protected $tags;
    /**
     * @ODM\String(name="answer")
     */
    protected $answer;

    /**
     * @ODM\Increment(name="solved")
     */
    protected $solved;

    /**
     * @ODM\Increment(name="attempts")
     */
    protected $attempts;

    /**
     * @ODM\Float(name="rating")
     */
    protected $rating;

    /**
     * @ODM\Int(name="difficulty")
     */
    protected $difficulty;

    /**
     * @ODM\Date(name="ta")
     */
    protected $timeAdded;

    /**
     * @ODM\ObjectId(name="postedby")
     */
    protected $postedById;

    /**
     * @ODM\NotSaved
     * Used to cache user's submission into problem object
     */
    protected $userSubmission;

    public static function create($title, $description, $answer, $difficulty, User $postedBy, array $tags = array())
    {
        $instance = new static();
        $instance->setTitle($title);
        $instance->setDescription($description);
        $instance->setAnswer($answer);
        $instance->setTags($tags);
        $instance->setSolved(0);
        $instance->setAttempts(0);
        $instance->setDifficulty($difficulty);
        $instance->setRating(0.0);
        $instance->setTimeAdded(new \DateTime());
        $instance->setPostedById(new \MongoId($postedBy->getId()));
        return $instance;
    }

    public function validate()
    {
        return true;
    }

    public function toArray()
    {
        return array(
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        );
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $attempts
     */
    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
    }

    /**
     * @return mixed
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * @param mixed $solved
     */
    public function setSolved($solved)
    {
        $this->solved = $solved;
    }

    /**
     * @return mixed
     */
    public function getSolved()
    {
        return $this->solved;
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
        return $this->rating;
    }

    /**
     * @param mixed $timeAdded
     */
    public function setTimeAdded($timeAdded)
    {
        $this->timeAdded = $timeAdded;
    }

    /**
     * @return mixed
     */
    public function getTimeAdded()
    {
        return $this->timeAdded;
    }

    /**
     * @param mixed $difficulty
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;
    }

    /**
     * @return mixed
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @param mixed $userSubmission
     */
    public function setUserSubmission($userSubmission)
    {
        $this->userSubmission = $userSubmission;
    }

    /**
     * @return mixed
     */
    public function getUserSubmission()
    {
        return $this->userSubmission;
    }

    /**
     * @param mixed $postedById
     */
    public function setPostedById($postedById)
    {
        $this->postedById = $postedById;
    }

    /**
     * @return mixed
     */
    public function getPostedById()
    {
        return $this->postedById;
    }
}
