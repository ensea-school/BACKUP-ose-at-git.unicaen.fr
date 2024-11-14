<?php

namespace Signature\Command;


use Application\Command\OseCommandAbstract;
use Monolog\Logger;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UnicaenSignature\Service\SignatureService;

class SignatureConfigCheckOseCommand extends OseCommandAbstract
{
    protected static $defaultName = 'signature:check-config-ose';



    protected function configure()
    {
        $this
            ->setDescription("Vérification de la configuration du système de signature éléctronique");
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getIO($input, $output);
        $io->title("Vérification de la configuration UnicaenSignature");
        $serviceSignature = $this->getServicemanager()->get(SignatureService::class);

        $serviceSignature->getLoggerService()->setVerbosity($output->getVerbosity());
        $default = false;
        $error   = false;

        // Logger
        $io->section("LOGGER");
        $config_logger = $serviceSignature->getSignatureConfigurationService()->getLoggerConfiguration();

        if (!array_key_exists('enable', $config_logger)) {
            $io->error("La configuration du logger est incomplète");

            return self::FAILURE;
        }

        $serviceSignature = $this->getServicemanager()->get(SignatureService::class);
        $log_enabled      = $config_logger['enable'];
        $io->text("Activé : " . ($config_logger["enable"] ? "✅" : "<red>non</red>"));
        if ($log_enabled) {
            $io->text("Stdout (développement) : " . ($config_logger["stdout"] ? "✅" : "<red>non</red>"));
            $io->text(
                "Level : " . $config_logger["level"] . " (" . Logger::getLevelName($config_logger['level']) . ")"
            );
            $io->text("File : " . $config_logger["file"]);
        }

        foreach (
            $serviceSignature->getSignatureConfigurationService()->getLetterfileConfiguration() as $config
        ) {
            if ($default === false && $config["default"] === true) {
                $default = sprintf('%s (<bold>%s</bold>)', $config["label"], $config['name']);
            }

            $io->section($config['label']);
            $io->text("Name : <bold>" . $config['name'] . "</bold>");
            $io->text("Par défaut : " . ($config["default"] ? "✅" : "<red>non</red>"));
            $io->text("Type de signature prise en charge : ");

            foreach ($config['levels'] as $level => $keyInLetterFile) {
                $levelInfos = $serviceSignature->getSignatureConfigurationService()->getLevelByName($level);
                $io->text(
                    sprintf(
                        " - <id>[%s]</id> : %s, <light>%s</light>)",
                        $levelInfos->getKey(),
                        $levelInfos->getLabel(),
                        $levelInfos->getDescription(),
                    )
                );
            }
            $io->newLine();
            $io->text("Configuration du parafeur : ");
            $headers = ["clef", "valeur", "type"];
            $rows    = [];
            foreach ($config['config'] as $key => $value) {
                if ($key == 'levels') continue;
                $row    = [$key, $value, gettype($value)];
                $rows[] = $row;
            }
            $io->table($headers, $rows);

            $io->text("Accès au parafeur : ");
            $accessOk = "Nop";
            $error    = false;
            try {
                $letterFile = $serviceSignature->getLetterfileService()->getLetterFileStrategy(
                    $config['name']
                );
                $output     = $letterFile->checkAccess();
                $io->success("$output");
            } catch (\Exception $e) {
                $io->error("Problème détécté sur le parafeur <bold>" . $config['name'] . "</bold> : " . $e->getMessage());
                $error = true;
            }
        }
        if ($error) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}