<?php

namespace App\Command;

use App\Entity\Statistic;
use App\Helper\ContainerParametersHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ParseUrlLogCommand extends Command
{
    /**
     * @var string $defaultName
     */
    protected static $defaultName = 'parse:url-log';

    /**
     * @var ContainerParametersHelper $parameterHelper
     */
    protected $parameterHelper;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var EntityManagerInterface $em
     */
    protected $em;

    public function __construct(
        $name = null,
        ContainerParametersHelper $parametersHelper,
        LoggerInterface $logger,
        EntityManagerInterface $em
    )
    {
        $this->parameterHelper = $parametersHelper;
        $this->logger = $logger;
        $this->em = $em;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Parsing url-file log');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $logFile = $this->parameterHelper->getApplicationRootDir() . '/logs/url.log';
        if(!is_file($logFile)) {
            $errorMessage = "Error executing command: {$this->getName()}. File: {$logFile} not found.";
            $this->logger->error($errorMessage);
            $io->error($errorMessage);
        } else {
            $handle = fopen($logFile, "r");
            $i = 0;
            while (!feof($handle)) {
                $buffer = trim(fgets($handle, 4096));
                $fields = explode('|', $buffer);
                if(sizeof($fields) !== 5) {
                    $io->warning("Unable to parse line: '{$buffer}'");
                } else {
                    $this->keeper($i)->send($fields);
                }
                $i++;
            }
            fclose($handle);
        }

        return 0;
    }

    /**
     * @param $counter
     * @return \Generator|void
     */
    private function keeper($counter)
    {
        while(true) {
            $data = yield;
            $row = new Statistic();
            $row->setIp($data[2]);
            $row->setUrlFrom($data[3]);
            $row->setUrlTo($data[4]);
            $row->setCreatedAt(new \DateTime($data[0] . ' ' . $data[1]));
            $this->em->persist($row);
            if($counter % 100 === 0) {
                $this->em->flush();
            }
        }
    }
}
