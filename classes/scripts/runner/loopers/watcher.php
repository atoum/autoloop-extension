<?php

namespace mageekguy\atoum\autoloop\scripts\runner\loopers;

use Lurker\Event\FilesystemEvent;
use Lurker\ResourceWatcher;
use mageekguy\atoum\autoloop\configuration;
use mageekguy\atoum\scripts\runner;
use mageekguy\atoum\scripts\runner\looper;

class watcher implements looper
{
    /**
     * @var configuration
     */
    protected $configuration;

    /**
     * @var runner
     */
    private $runner;

    /**
     * @param runner $runner
     */
    public function __construct(runner $runner)
    {
        $this->runner = $runner;
    }

    /**
     * @return string
     */
    public function runAgain()
    {
        /** @var \mageekguy\atoum\writers\std\out $outputWriter */
        $outputWriter = $this->runner->getOutputWriter();

        $watcher = new ResourceWatcher;

        $onEvent = function(FilesystemEvent $event) use ($watcher, $outputWriter) {
            $outputWriter->write((string) $event->getResource() . " has been modified." . PHP_EOL);
            $watcher->stop();
        };

        foreach ($this->configuration->getWatchedFiles() as $watchedFile) {
            $watcher->track($watchedFile, $watchedFile);
            $watcher->addListener($watchedFile, $onEvent);
        }

        foreach ($this->runner->getRunner()->getTestPaths() as $path) {
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
