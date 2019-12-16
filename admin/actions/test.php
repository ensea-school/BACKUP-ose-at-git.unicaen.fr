<?php

$bdd    = new \BddAdmin\Bdd(Config::get('bdds', 'dev-local'));
$schema = new \BddAdmin\Schema($bdd);

$bdd2 = new \BddAdmin\Bdd(Config::get('bdds', 'dev-local'));
//$oa->migration('post', 'FormuleTestStructureToString');

\BddAdmin\Event\EventManager::getMain()->listen(null, null, function ($event) {
    var_dump(get_class($event->sender), $event->action, $event->data);
    //$event->setReturn('no-exec', true);
});

//$r = $bdd->select('select * from annee');

$views = $schema->getDdlObject(\BddAdmin\Ddl\DdlView::class);
$views->create(['name' => 'test', 'definition' => 'CREATE OR REPLACE VIEW test AS SELECT * FROM type_intervention']);
$views->execQueries();