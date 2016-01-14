<?php

namespace mageekguy\atoum\autoloop;

use Illuminate\Filesystem\Filesystem;
use JasonLewis\ResourceWatcher\Event;
use JasonLewis\ResourceWatcher\Resource\FileResource;
use JasonLewis\ResourceWatcher\Tracker;
use JasonLewis\ResourceWatcher\Watcher;

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

        $watcher = new Watcher(new Tracker, new Filesystem);
        /** @var \mageekguy\atoum\writers\std\out $outputWriter */
        $outputWriter = $this->getOutputWriter();

        $onEvent = function(Event $event, FileResource $fileResource, $path) use ($watcher, $outputWriter) {
            $outputWriter->write($fileResource->getPath() . " has been modified." . PHP_EOL);
            $watcher->stop();
        };

        foreach ($this->configuration->getWatchedFiles() as $watchedFile) {
            $watcher->watch($watchedFile)->onAnything($onEvent);
        }

        foreach ($this->getRunner()->getTestPaths() as $path) {
            $watcher->watch($path)->onAnything($onEvent);
        }

        $outputWriter->write('Waiting for a file to change to run the test(s)... (Use CTRL+C to stop)'. PHP_EOL);

        $watcher->start(10000);

        return '';
    }

    /**
     * @return \mageekguy\atoum\runner
     */
    public function getRunner()
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
