<?php

namespace mageekguy\atoum\autoloop;

use Lurker\Event\FilesystemEvent;
use Lurker\ResourceWatcher;

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

        /** @var \mageekguy\atoum\writers\std\out $outputWriter */
        $outputWriter = $this->getOutputWriter();

        $watcher = new ResourceWatcher;

        $onEvent = function(FilesystemEvent $event) use ($watcher, $outputWriter) {
            $outputWriter->write((string) $event->getResource() . " has been modified." . PHP_EOL);
            $watcher->stop();
        };

        foreach ($this->configuration->getWatchedFiles() as $watchedFile) {
            $watcher->track($watchedFile, $watchedFile);
            $watcher->addListener($watchedFile, $onEvent);
        }

        foreach ($this->getRunner()->getTestPaths() as $path) {
            $watcher->track($path, $path);
            $watcher->addListener($path, $onEvent);
        }

        $outputWriter->write('Waiting for a file to change to run the test(s)... (Use CTRL+C to stop)'. PHP_EOL);

        $watcher->start();

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
