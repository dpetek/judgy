<?php

namespace FileManager\Storage;

use FileManager\File\File;

interface StorageInterface
{
    /**
     * @param File $file
     * @return bool
     *
     * Method returns true if file is successfully saved and false otherwise.
     */
    public function save(File $file);

    /**
     * @param $filename string
     * @return bool
     *
     * Method returns true if file is successfully removed and false otherwise.
     */
    public function remove($filename);

    /**
     * @param $filename string
     * @return File|null
     *
     * Method returns a file if found and null otherwise.
     */
    public function get($filename);
}
