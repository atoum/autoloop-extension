<?php

namespace mageekguy\atoum\autoloop\scripts\loop\strategy;

use Lurker\Event\FilesystemEvent;
use Lurker\ResourceWatcher;
use mageekguy\atoum\autoloop\configuration;

class watcher implements \mageekguy\atoum\scripts\loop\strategy
{
    /**
     * @var configuration
     */
    protected $configuration;

    /**
     * @param string $message
     *
     * @return string
     */
    public function runAgain(\mageekguy\atoum\scripts\runner $runner)
    {
        /** @var \mageekguy\atoum\writers\std\out $outputWriter */
        $outputWriter = $runner->getOutputWriter();

        $watcher = new ResourceWatcher;

        $onEvent = function(FilesystemEvent $event) use ($watcher, $outputWriter) {
            $outputWriter->write($event->getResource()->getId() . " has been modified." . PHP_EOL);
            $watcher->stop();
        };

        foreach ($this->configuration->getWatchedFiles() as $watchedFile) {
            $watcher->track($watchedFile, $watchedFile);
            $watcher->addListener($watchedFile, $onEvent);
        }

        foreach ($runner->getRunner()->getTestPaths() as $path) {
            $watcher->track($path, $path);
            $watcher->addListener($path, $onEvent);
        }

        $outputWriter->write('Waiting for a file to change to run the test(s)... (Use CTRL+C to stop)'. PHP_EOL);

        $watcher->start();

        return '';
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
