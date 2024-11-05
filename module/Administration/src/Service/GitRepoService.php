<?php

namespace Administration\Service;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


/**
 * Description of GitRepoService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class GitRepoService
{
    const OSE_ORIGIN   = 'https://git.unicaen.fr/open-source/OSE.git';
    const VERSION_FILE = 'VERSION';
    const MIN_VERSION  = 17; // version minimum installable


    private array|bool $tags     = false;
    private array|bool $branches = false;
    protected string   $oldVersion;
    protected string   $version;



    public function __construct()
    {
        $this->oldVersion = $this->currentVersion();
    }



    private function exec(string $command): string
    {
        $process = new Process(explode(' ', $command));

        try {
            $process->mustRun();
            return $process->getOutput();
        } catch (ProcessFailedException $exception) {
            throw new \RuntimeException('Erreur lors de l\'exécution de la commande : ' . $exception->getMessage());
        }
    }



    public function gitlabIsReachable(): bool
    {
        return $this->brancheIsValid('master');
    }



    public function getTags($minVersion = self::MIN_VERSION): array
    {
        if (false === $this->tags) {
            $this->tags = [];

            $ts = explode("\n",trim($this->exec("git ls-remote --tags --refs " . self::OSE_ORIGIN, false)));
            foreach ($ts as $tag) {
                $tag              = substr($tag, strpos($tag, 'refs/tags/') + 10);
                $this->tags[$tag] = $this->extractTagInfos($tag);
            }

            uasort($this->tags, function ($a, $b) {
                $oa = $this->tagOrderString($a);
                $ob = $this->tagOrderString($b);

                return $oa > $ob ? 1 : -1;
            });
        }

        $tags = $this->tags;

        foreach ($tags as $tag => $infos) {
            if ($infos['major'] < $minVersion) unset($tags[$tag]);
            if (isset($tags[$tag]) && $infos['beta']) {
                $stableFound = false;
                foreach ($this->tags as $t => $i) {
                    if ($t !== $tag && !$i['beta']) {
                        if (str_starts_with($tag, $t)) {
                            $stableFound = true;
                        }
                    }
                }
                if ($stableFound) {
                    unset($tags[$tag]);
                }
            }
        }

        return $tags;
    }



    public function getBranches(): array
    {
        if (false === $this->branches) {
            $this->branches = [];

            $bs = explode("\n",trim($this->exec("git ls-remote --heads --refs " . self::OSE_ORIGIN, false)));
            foreach ($bs as $branche) {
                $this->branches[] = substr($branche, strpos($branche, 'refs/heads/') + 11);
            }

            sort($this->branches);
        }

        return $this->branches;
    }



    public function getCurrentBranche(): ?string
    {
        $ts = explode("\n",trim($this->exec("git branch", false)));
        foreach ($ts as $t) {
            if (0 === strpos($t, '*')) {
                return trim(substr($t, 1));
            }
        }

        return null;
    }



    public function tagIsValid(string $tag): bool
    {
        $tags = $this->getTags();
        foreach ($tags as $t) {
            if ($t['tag'] == $tag) {
                return true;
            }
        }
        return false;
    }



    public function brancheIsValid(string $branche): bool
    {
        return in_array($branche, $this->getBranches());
    }



    public function oldVersion(): string
    {
        return $this->oldVersion;
    }



    public function currentVersion(): string
    {
        if (!file_exists(self::VERSION_FILE)) {
            return 'inconnue';
        }

        return trim(file_get_contents(self::VERSION_FILE));
    }



    public function writeVersion(string $version)
    {
        $this->version = $version;
        file_put_contents(self::VERSION_FILE, $version);
    }



    protected function extractTagInfos(string $tag): array
    {
        $i = [
            'tag'     => $tag,
            'major'   => 0,
            'minor'   => 0,
            'rev'     => 0,
            'beta'    => false,
            'betaNum' => null,
            'other'   => null,
        ];

        if ((string)(int)$tag === $tag) {
            // uniquement une version majeure
            $i['major'] = (int)$tag;
            return $i;
        }


        if (false !== strpos($tag, '-')) {
            $txt = substr($tag, strpos($tag, '-') + 1);

            if (str_starts_with(strtolower($txt), 'beta')) {
                $btxt = substr($txt, 4);

                if ($btxt === '') {
                    $i['beta']    = true;
                    $i['betaNum'] = 0;
                } elseif ((int)$btxt != 0) {
                    $i['beta']    = true;
                    $i['betaNum'] = (int)$btxt;
                } else {
                    $i['other'] = $txt;
                }
            } else {
                $i['other'] = $txt;
            }

            // ne reste que le numéro de version
            $tag = substr($tag, 0, strpos($tag, '-'));
        }

        $nums = explode('.', $tag);

        if (isset($nums[0])) {
            $i['major'] = (int)$nums[0];
        }
        if (isset($nums[1])) {
            $i['minor'] = (int)$nums[1];
        }
        if (isset($nums[2])) {
            $i['rev'] = (int)$nums[2];
        }

        return $i;
    }



    protected function tagOrderString(array $infos): string
    {
        return str_pad((string)$infos['major'], 5, '0', STR_PAD_LEFT)
            . 'x' . str_pad((string)$infos['minor'], 5, '0', STR_PAD_LEFT)
            . 'x' . str_pad((string)$infos['rev'], 5, '0', STR_PAD_LEFT)
            . 'x' . ($infos['beta'] ? '0' : '1')
            . 'x' . str_pad((string)$infos['betaNum'], 5, '0', STR_PAD_LEFT)
            . 'x' . $infos['other'];
    }
}