<?php

namespace mageekguy\atoum\autoloop;

class prompt extends \mageekguy\atoum\script\prompt
{
    /**
     * @var \mageekguy\atoum\runner
     */
    protected $runner;

    /**
     * @var configuration
     */
    protected $configuration;

    /**
     * @param string $message
     *
     * @return string
     */
    public function ask($message)
    {

        $runAgainText = "Press <Enter> to reexecute, press any other key and <Enter> to stop...";
        if ($message != $runAgainText) {
            return parent::ask($message);
        }

        $files = new \Illuminate\Filesystem\Filesystem;

        $tracker = new \JasonLewis\ResourceWatcher\Tracker;

        $watcher = new \JasonLewis\ResourceWatcher\Watcher($tracker, $files);

        $onEvent = function(\JasonLewis\ResourceWatcher\Event $event, \JasonLewis\ResourceWatcher\Resource\FileResource $fileResource, $path) use ($watcher) {
            echo $fileResource->getPath() . " has been modified.".PHP_EOL;
            $watcher->stop();
        };


        foreach ($this->configuration->getWatchedFiles() as $watchedFile) {
            $watcher->watch($watchedFile)->onAnything($onEvent);
        }

        foreach ($this->gerRunner()->getTestPaths() as $path) {
            $watcher->watch($path)->onAnything($onEvent);
        }

        echo 'Waiting for a file to change to run the test(s)... (Use CTRL+C to stop)' . PHP_EOL;

        $watcher->start(10000);

        return '';
    }

    /**
     * @return \mageekguy\atoum\runner
     */
    public function gerRunner()
    {
        return $this->runner;
    }

    /**
     * @param $runner
     */
    public function setRunner($runner)
    {
        $this->runner = $runner;
    }

    /**
     * @return configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param configuration $configuration
     *
     * @return $this
     */
    public function setConfiguration(configuration $configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }
}
