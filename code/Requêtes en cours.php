<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$bdd = OseAdmin::instance()->getBdd();

$sql = "
SELECT
  s.sid                   sid,
  s.serial#               serial,
  a.sql_fulltext          requete_sql,
  
  a.first_load_time       debut_requete,
  s.seconds_in_wait       temps_attente,
  a.plsql_exec_time       temps_execution,
      
  s.osuser                utilisateur,
  s.program               client,
  o.object_type || ' ' || o.object_name objet_bloquant,
  s.event                 evenement
  
FROM
  v\$sqlarea a
  JOIN v\$session s ON s.sql_hash_value = a.hash_value AND s.sql_address = a.address
  LEFT JOIN DBA_OBJECTS o ON o.object_id = s.row_wait_obj#
WHERE
  s.username IS NOT NULL
  AND a.sql_text NOT LIKE '%NO_GET_SQL%'
ORDER BY
  s.wait_time_micro

";

$res = $bdd->select($sql);

echo '<h1>Requêtes SQL en cours d\'exécution sur la base de données</h1>';
echo '<div style="font-weight:bold">' . count($res) . ' requête(s) en cours</div>';
echo '<br />';
echo '<br />';
foreach ($res as $s) {
    ?>
    <b>SID : </b><?= $s['SID'] ?>,
    <b>serial : </b><?= $s['SERIAL'] ?>,
    <br/>
    <b>Début le : </b><?= $s['DEBUT_REQUETE'] ?>,
    <b>Temps d'attente : </b><?= $s['TEMPS_ATTENTE'] ?> secondes,
    <b>Temps d'exécution : </b><?= $s['TEMPS_EXECUTION'] ?>,
    <br/>
    <b>Utilisateur : </b><?= $s['UTILISATEUR'] ?>,
    <b>Client : </b><?= $s['CLIENT'] ?>,
    <br/>
    <b>Objet qui bloque : </b><?= $s['OBJET_BLOQUANT'] ?>,
    <b>Evénement : </b><?= $s['EVENEMENT'] ?>,
    <?php
    sqlDump($s['REQUETE_SQL']);
}

?>
<script>

    setTimeout(function () { window.location.reload(); }, 10000);

</script>
