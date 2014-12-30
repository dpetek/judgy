<?php

namespace FileManager\File;

class File
{
    // compress
    const EXTENSION_ZIP = 'zip';

    // code
    const EXTENSION_CPP = 'cpp';
    const EXTENSION_GO  = 'go';
    const EXTENSION_JAVA = 'java';

    // input output
    const EXTENSION_INPUT = 'in';
    const EXTENSION_OUTPUT = 'out';

    // text
    const EXTENSION_TXT = 'txt';
    const EXTENSION_HTML = 'html';
    const EXTENSION_HTM = 'htm';
    const EXTENSION_MARKDOWN = 'md';

    protected $content;

    protected $path;

    protected $filename;

    protected $baseName;

    protected $directory;

    protected $extension;

    public function __construct($path, $content)
    {
        $this->setContent($content);
    }

    protected function processFilePath($path)
    {
        $info = pathinfo($path);
        $this->setExtension(strtolower($info[PATHINFO_EXTENSION]));
        $this->setDirectory($info[PATHINFO_DIRNAME]);
        $this->setFilename($info[PATHINFO_FILENAME]);
        $this->setBaseName($info[PATHINFO_BASENAME]);
        $this->setPath($path);
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $baseName
     */
    public function setBaseName($baseName)
    {
        $this->baseName = $baseName;
    }

    /**
     * @return mixed
     */
    public function getBaseName()
    {
        return $this->baseName;
    }
}

