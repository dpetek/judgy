<?php

namespace FileManager\Storage;

use FileManager\File\File;

class LocalFileStorage implements StorageInterface
{
    protected $baseLocation;

    public function __construct($baseLocation = '/tmp')
    {
        $this->setBaseLocation($baseLocation);
        self::createLocation($baseLocation);
    }

    protected static function createLocation($location, $permission = 0x777)
    {
        mkdir($location, $permission, true);
    }

    /**
     * @param File $file
     * @return bool
     *
     * Method returns true if file is successfully saved and false otherwise.
     */
    public function save(File $file)
    {
        $ret = @file_put_contents(
            $this->getFullPathForFilename($file->getName()),
            $file->getContent()
        );
        return ($ret !== false);
    }

    /**
     * @param $filename string
     * @return bool
     *
     * Method returns true if file is successfully removed and false otherwise.
     */
    public function remove($filename)
    {
        return @unlink($this->getFullPathForFilename($filename));
    }

    /**
     * @param $filename string
     * @return File|null
     *
     * Method returns a file if found and null otherwise.
     */
    public function get($filename)
    {
        $content = @file_get_contents($this->getFullPathForFilename($filename));
        if (is_null($content)) {
            return null;
        }
        return new File($filename, $content);
    }

    protected function getFullPathForFilename($filename)
    {
        return trim($this->getBaseLocation(), '/') . '/' . $filename;
    }

    /**
     * @param mixed $baseLocation
     */
    public function setBaseLocation($baseLocation)
    {
        $this->baseLocation = $baseLocation;
    }

    /**
     * @return mixed
     */
    public function getBaseLocation()
    {
        return $this->baseLocation;
    }
}
