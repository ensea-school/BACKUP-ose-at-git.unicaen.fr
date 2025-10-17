<?php

namespace Administration\Migration;

use Unicaen\BddAdmin\Migration\MigrationAction;
use Unicaen\Framework\Application\Application;
use Workflow\Service\WorkflowService;

class v25Workflow extends MigrationAction
{


    public function description(): string
    {
        return "Suppression de doublons dans les validations";
    }



    public function utile(): bool
    {
        return $this->manager()->hasNew('table', 'WORKFLOW_ETAPE');
    }



    public function before()
    {
        $this->getBdd()->exec('DROP TABLE TBL_WORKFLOW CASCADE CONSTRAINTS');
        $this->getBdd()->exec('DROP TABLE TBL_VALIDATION_ENSEIGNEMENT CASCADE CONSTRAINTS');
        $this->getBdd()->exec('DROP TABLE TBL_VALIDATION_REFERENTIEL CASCADE CONSTRAINTS');
    }



    public function after()
    {
        $bdd = $this->getBdd();

        try {
            $bdd->data()->addAction('workflow-reset', 'Réinitialisation du workflow');

            $config = $bdd->data()->getOption('config');
            // ignore plus ces colonnes en cas d'update afin de tout remettre à l'état initial
            unset($config['WORKFLOW_ETAPE']['options']['update-ignore-cols']);
            $bdd->data()->setOption('config', $config);
            $bdd->data()->run('workflow-reset');
            $this->logSuccess('Le nouveau workflow a été initialisé avec des paramétrages par défaut, il vous faudra le configurer par vous-même');
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }
    }
}
