<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


$bdd = new \Application\Connecteur\Bdd\BddConnecteur();
$bdd->setEntityManager($container->get(\Application\Constants::BDD));

/*
 *
 * select
  S.USERNAME,
  s.sid, s.serial#,
  s.program,s.osuser,
  t.sql_id,
  sql_text
from
  v$sqltext_with_newlines t,
  V$SESSION s
where
  t.address =s.sql_address
  and t.hash_value = s.sql_hash_value
  and s.status = 'ACTIVE'
  and s.username <> 'SYSTEM'
order by
  s.sid,
  t.piece
 */


$sql = "
  SELECT 
    sesion.sid,
    sesion.username,
    cpu_time,
    elapsed_time,
    sql_text
  FROM
    v\$sqlarea sqlarea
    JOIN v\$session sesion ON sesion.sql_hash_value = sqlarea.hash_value AND sesion.sql_address = sqlarea.address 
  WHERE 
    sesion.username IS NOT NULL
    AND sql_text NOT LIKE '%NO_GET_SQL%'
";

$res = $bdd->fetch($sql);

echo '<div style="font-weight:bold">' . count($res) . ' requête(s) en cours</div>';

foreach ($res as $s) {
    echo 'SID=' . $s['SID'] . ', USERNAME=' . $s['USERNAME'] . ', CPU_TIME=' . $s['CPU_TIME'] . ', ELAPSED_TIME=' . $s['ELAPSED_TIME'];
    sqlDump($s['SQL_TEXT']);
}

?>
<script>

    setTimeout(function () { window.location.reload(); }, 10000);

</script>
