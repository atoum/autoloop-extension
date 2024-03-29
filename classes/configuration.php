<?php

namespace atoum\atoum\autoloop;

class configuration implements \atoum\atoum\extension\configuration
{
    /**
     * @var array
     */
    private $watchedFiles = array();

    /**
     * @return array
     */
    public function serialize()
    {
        return array(
            'watched_files' => serialize($this->watchedFiles),
        );
    }

    /**
     * @param array $config
     *
     * @return configuration
     */
    public static function unserialize(array $config)
    {
        $configuration = new static;

        if (false !== ($clientConfiguration = unserialize($config['watched_files']))) {
            $configuration->set($clientConfiguration);
        }

        return $configuration;
    }

    /**
     * @param array $watchedFiles
     *
     * @return $this
     */
    public function setWatchedFiles(array $watchedFiles = array())
    {
        $this->watchedFiles = $watchedFiles;

        return $this;
    }

    /**
     * @return array
     */
    public function getWatchedFiles()
    {
        return $this->watchedFiles;
    }
}
