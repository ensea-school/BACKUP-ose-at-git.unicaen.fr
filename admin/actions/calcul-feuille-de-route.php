<?php

/** @var \Application\Service\WorkflowService $ws */
$ws = $oa->container()->get(\Application\Service\WorkflowService::class);

$id = (int)$c->getArg(2);

if (0 === $id){
    $c->printDie('L\'ID valide d\'un intervenant doit être transmis en argument');
}

$intervenant = $ws->getEntityManager()->find(\Application\Entity\Db\Intervenant::class, $id);

if (!$intervenant){
    $c->printDie('L\'intervenant dont l\'ID est '.$id.' n\'existe pas');
}

$c->printMainTitle('Feuille de route de '.$intervenant);

$c->println('Année universitaire '.$intervenant->getAnnee());
$c->println('Statut '.lcfirst($intervenant->getStatut()));


$c->print('Calcul en cours ...');
$ws->calculerTableauxBord([], $intervenant);
$c->print("\r");

if (empty($errors)) {
    $c->println('Feuille de route actualisée', $c::COLOR_GREEN );
} else {
    foreach ($errors as $error) {
        $c->error($error);
    }
}