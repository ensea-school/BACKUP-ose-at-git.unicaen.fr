<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;

/**
 * Vérification de l'espace disque disponible sur le filer OSE.
 */
class VerifierEspaceDisqueCommand extends Command
{
    use MailServiceAwareTrait;

    private const DEFAULT_THRESHOLD = 90;



    protected function configure(): void
    {
        $this
            ->setDescription('Vérification de l\'espace disque disponible sur le point de montage des fichiers OSE')
            ->addOption('mount', 'm', InputOption::VALUE_REQUIRED, 'Chemin à vérifier. Par défaut : configuration fichiers.dir')
            ->addOption('seuil', 's', InputOption::VALUE_REQUIRED, 'Seuil d\'alerte en pourcentage d\'occupation', self::DEFAULT_THRESHOLD)
            ->addOption('to', 't', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Destinataire du mail. Option répétable ou liste séparée par des virgules');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $threshold  = (int)$input->getOption('seuil');

        if ($threshold < 1 || $threshold > 100) {
            $io->error('Le seuil doit être compris entre 1 et 100.');
            return Command::FAILURE;
        }

        try {
            $path = $this->getPathToCheck($input->getOption('mount'));
            $recipients = $this->getRecipients((array)$input->getOption('to'));
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $result = $this->checkDisk($path, $threshold);

        $io->definitionList(
            ['Chemin vérifié' => $result['path']],
            ['Point de montage' => $result['mountPoint']],
            ['Statut' => $result['status']],
            ['Occupation' => $result['usedPercentLabel']],
            ['Espace total' => $result['totalLabel']],
            ['Espace utilisé' => $result['usedLabel']],
            ['Espace disponible' => $result['freeLabel']],
            ['Seuil' => $threshold . ' %']
        );

        try {
            if($result['sendMail'])
            {
                $this->sendResultMail($recipients, $result);
                $io->success('Résultat envoyé par mail à ' . implode(', ', $recipients) . '.');
            }
        } catch (\Exception $e) {
            $io->error('Impossible d\'envoyer le mail de résultat : ' . $e->getMessage());
            return Command::FAILURE;
        }

        if ($result['isError'] || $result['isAlert']) {
            $io->error($result['message']);
            return Command::FAILURE;
        }

        $io->success($result['message']);
        return Command::SUCCESS;
    }



    private function getPathToCheck(?string $path): string
    {
        if ($path === null || trim($path) === '') {
            $config = \AppAdmin::config();
            if (($config['fichiers']['stockage'] ?? null) !== 'file') {
                throw new \RuntimeException('Le stockage des fichiers OSE n\'est pas configuré en mode "file".');
            }

            $path = $config['fichiers']['dir'] ?? null;
        }

        if (!$path) {
            throw new \RuntimeException('Aucun répertoire de stockage des fichiers OSE n\'est configuré.');
        }

        return rtrim((string)$path, '/') ?: '/';
    }



    private function getRecipients(array $optionRecipients): array
    {
        $recipients = [];
        foreach ($optionRecipients as $recipient) {
            foreach (explode(',', (string)$recipient) as $email) {
                $email = trim($email);
                if ($email !== '') {
                    $recipients[] = $email;
                }
            }
        }

        if (empty($recipients)) {
            $recipients = (array)(\AppAdmin::config()['mail']['redirection'] ?? []);
        }

        $recipients = array_values(array_unique($recipients));
        if (empty($recipients)) {
            throw new \RuntimeException('Aucun destinataire défini. Utilisez l\'option --to.');
        }

        return $recipients;
    }



    private function checkDisk(string $path, int $threshold): array
    {
        $isError = false;
        $errors  = [];
        $mountPoint = $path;
        $sendMail = false;

        if (!is_dir($path)) {
            $isError = true;
            $errors[] = 'Le répertoire à vérifier n\'existe pas.';
        }

        if (!$isError) {
            $mountPoint = $this->resolveMountPoint($path);
        }

        $total = !$isError ? disk_total_space($path) : false;
        $free  = !$isError ? disk_free_space($path) : false;

        if ($total === false || $free === false || $total <= 0) {
            $isError = true;
            $errors[] = 'Impossible de lire les informations d\'espace disque.';
            $total = 0;
            $free  = 0;
        }

        $used        = max(0, $total - $free);
        $usedPercent = $total > 0 ? ($used / $total) * 100 : 0.0;
        $isAlert     = !$isError && $usedPercent >= $threshold;
        $status      = $isError ? 'ERREUR' : ($isAlert ? 'ALERTE' : 'OK');

        if ($isError) {
            $message = implode(' ', $errors);
        } elseif ($isAlert) {
            $sendMail = true;
            $message = sprintf(
                'Le point de montage %s atteint %.2f %% d\'occupation, au-dessus du seuil de %d %%.',
                $mountPoint,
                $usedPercent,
                $threshold
            );
        } else {
            $message = sprintf(
                'Le point de montage %s n\'est pas saturé : %.2f %% d\'occupation pour un seuil de %d %%.',
                $mountPoint,
                $usedPercent,
                $threshold
            );
        }

        return [
            'path'             => $path,
            'mountPoint'       => $mountPoint,
            'threshold'        => $threshold,
            'status'           => $status,
            'isError'          => $isError,
            'isAlert'          => $isAlert,
            'sendMail'         => $sendMail,
            'message'          => $message,
            'totalLabel'       => $this->formatBytes((float)$total),
            'usedLabel'        => $this->formatBytes((float)$used),
            'freeLabel'        => $this->formatBytes((float)$free),
            'usedPercentLabel' => sprintf('%.2f %%', $usedPercent),
        ];
    }



    private function resolveMountPoint(string $path): string
    {
        $path = realpath($path) ?: $path;
        if (!is_readable('/proc/mounts')) {
            return $path;
        }

        $mounts = file('/proc/mounts', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($mounts === false) {
            return $path;
        }

        $mountPoints = [];
        foreach ($mounts as $mount) {
            $parts = preg_split('/\s+/', $mount);
            if (!isset($parts[1])) {
                continue;
            }

            $mountPoints[] = str_replace(['\040', '\011', '\012', '\134'], [' ', "\t", "\n", '\\'], $parts[1]);
        }

        usort($mountPoints, static fn(string $a, string $b) => strlen($b) <=> strlen($a));
        foreach ($mountPoints as $mountPoint) {
            $normalizedMountPoint = rtrim($mountPoint, '/') ?: '/';
            if ($normalizedMountPoint === '/') {
                return '/';
            }

            if ($path === $normalizedMountPoint || str_starts_with($path, $normalizedMountPoint . '/')) {
                return $normalizedMountPoint;
            }
        }

        return $path;
    }



    private function sendResultMail(array $recipients, array $result): void
    {
        $subject = sprintf(
            '[%s] Espace disque OSE %s',
            $result['status'],
            $result['mountPoint']
        );

        $body = sprintf(
            '<p>%s</p>'
            . '<table border="1" cellpadding="6" cellspacing="0">'
            . '<tr><th align="left">Chemin vérifié</th><td>%s</td></tr>'
            . '<tr><th align="left">Point de montage</th><td>%s</td></tr>'
            . '<tr><th align="left">Statut</th><td>%s</td></tr>'
            . '<tr><th align="left">Occupation</th><td>%s</td></tr>'
            . '<tr><th align="left">Espace total</th><td>%s</td></tr>'
            . '<tr><th align="left">Espace utilisé</th><td>%s</td></tr>'
            . '<tr><th align="left">Espace disponible</th><td>%s</td></tr>'
            . '<tr><th align="left">Seuil</th><td>%d %%</td></tr>'
            . '</table>',
            htmlspecialchars($result['message']),
            htmlspecialchars($result['path']),
            htmlspecialchars($result['mountPoint']),
            htmlspecialchars($result['status']),
            htmlspecialchars($result['usedPercentLabel']),
            htmlspecialchars($result['totalLabel']),
            htmlspecialchars($result['usedLabel']),
            htmlspecialchars($result['freeLabel']),
            $result['threshold']
        );

        $from = $this->getMailFrom();

        $email = (new Email())
            ->from($from)
            ->to(...$recipients)
            ->subject($subject)
            ->html($body)
            ->text(strip_tags(str_replace(['</tr>', '</p>'], ["\n", "\n"], $body)));

        $this->getMailService()->getMailer()->send($email);
    }



    private function getMailFrom(): Address
    {
        $mailConfig = \AppAdmin::config()['mail'] ?? [];
        $fromEmail  = $mailConfig['from'] ?? null;
        $fromName   = 'Application OSE';

        if (!$fromEmail) {
            $unicaenMailConfig = $this->getMailService()->getConfig();
            $fromEmail = $unicaenMailConfig['module']['default']['from_email'] ?? $unicaenMailConfig['from_email'] ?? null;
            $fromName  = $unicaenMailConfig['module']['default']['from_name'] ?? $unicaenMailConfig['from_name'] ?? $fromName;
        }

        if (!$fromEmail) {
            throw new \RuntimeException('Aucun expéditeur mail configuré.');
        }

        return new Address($fromEmail, $fromName);
    }



    private function formatBytes(float $bytes): string
    {
        $units = ['o', 'Ko', 'Mo', 'Go', 'To', 'Po'];
        $index = 0;

        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }

        return sprintf('%.2f %s', $bytes, $units[$index]);
    }
}
