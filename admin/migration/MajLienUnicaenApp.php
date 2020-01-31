<?php





class MajLienUnicaenApp extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_POST;



    public function description(): string
    {
        return "Mise à jour du lien vers les fichier publics d'UnicaenApp";
    }



    public function utile(): bool
    {
        $oseDir = $this->manager->getOseAdmin()->getOseDir();

        return file_exists($oseDir . "public/vendor/unicaen/app/unicaen");
    }



    public function action(string $contexte)
    {
        $this->manager->getOseAdmin()->run('maj-public-links', true);
    }

}