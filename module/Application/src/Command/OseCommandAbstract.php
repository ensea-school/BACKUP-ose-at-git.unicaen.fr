<?php

namespace Application\Command;


use Laminas\ServiceManager\ServiceManager;
use OseAdmin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class OseCommandAbstract extends Command
{

    /** @var ServiceManager ServiceManager */
    private $servicemanager;

    private $oseAdmin;



    /**
     * OscarCommandAbstract constructor.
     */
    public function __construct(ServiceManager $sm, OseAdmin $oseAdmin)
    {
        $this->servicemanager = $sm;
        $this->oseAdmin       = $oseAdmin;
        parent::__construct();
    }



    /**
     * @return ServiceManager
     */
    protected function getServicemanager()
    {
        return $this->servicemanager;
    }



    protected function getOseAdmin()
    {
        return $this->oseAdmin;
    }



    public function addOutputStyle(OutputInterface $output)
    {
        $outputStyle = new OutputFormatterStyle('cyan', 'default', ['bold']);
        $output->getFormatter()->setStyle('id', $outputStyle);

        $outputStyle = new OutputFormatterStyle('red', 'default', ['bold']);
        $output->getFormatter()->setStyle('red', $outputStyle);

        $outputStyle = new OutputFormatterStyle('blue', 'default', ['underscore']);
        $output->getFormatter()->setStyle('link', $outputStyle);

        $outputStyle = new OutputFormatterStyle('default', 'default', ['bold']);
        $output->getFormatter()->setStyle('bold', $outputStyle);

        $outputStyle = new OutputFormatterStyle('yellow', 'default', ['bold']);
        $output->getFormatter()->setStyle('none', $outputStyle);

        $outputStyle = new OutputFormatterStyle('red', 'default', ['bold']);
        $output->getFormatter()->setStyle('title', $outputStyle);

        $outputStyle = new OutputFormatterStyle('green', 'default', ['bold']);
        $output->getFormatter()->setStyle('green', $outputStyle);
    }



    public function getIO(InputInterface $input, OutputInterface $output, bool $extraStyle = true): SymfonyStyle
    {
        if ($extraStyle) {
            $this->addOutputStyle($output);
        }

        return new SymfonyStyle($input, $output);
    }



    public function exec($command)
    {
        if (is_array($command)) {
            $command = implode(';', $command);
        }

        exec($command, $output, $return);

        return $output;
    }
}
