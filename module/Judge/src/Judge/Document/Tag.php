<?php

namespace Judge\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Api\ApiInterface\IResponse;
use Core\Document\Base;

/**
 * @ODM\Document(db="judge",collection="Tags",slaveOkay=false, repositoryClass="Judge\Repository\Tag")
 */
class Tag extends Base implements IResponse
{
    /**
     * @ODM\String(name="text")
     */
    protected $text;

    /**
     * @ODM\String(name="realText")
     */
    protected $realText;

    public static function create($text)
    {
        $text = self::normalizeText($text);
        if (!$text) {
            // todo throw an exception
            return null;
        }
        $instance = new self();
        $instance->setText($text);
        $instance->setRealText($text);
        return $instance;
    }

    public static function normalizeText($text)
    {
        $text = strtolower($text);
        $text = preg_replace("/[^a-z0-9]/", '', $text);
        return $text;
    }

    public function validate()
    {
        // TODO: Implement validate() method.
    }

    public function toArray()
    {
        return array(
            'id' => (string)$this->getId(),
            'text' => $this->getRealText()
        );
    }

    /**
     * @param mixed $realText
     */
    public function setRealText($realText)
    {
        $this->realText = $realText;
    }

    /**
     * @return mixed
     */
    public function getRealText()
    {
        return $this->realText;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }
}
