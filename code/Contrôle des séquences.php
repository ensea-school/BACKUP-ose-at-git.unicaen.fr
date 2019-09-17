<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Interop\Container\ContainerInterface
 */

$bdd = new \Application\Connecteur\Bdd\BddConnecteur();
$bdd->setEntityManager($sl->get(\Application\Constants::BDD));


$tables = getData($bdd);

$tableToModif = isset($_REQUEST['table-to-modif']) ? $_REQUEST['table-to-modif'] : null;

if ($tableToModif && isset($tables[$tableToModif]) && !empty($tables[$tableToModif]['sql'])) {
    foreach ($tables[$tableToModif]['sql'] as $query) {
        $bdd->exec($query);
    }
    die('OK');
}

?>
    <h1>Contrôle des valeurs des séquences</h1>
    <table class="table table-bordered table-condensed table-hover">
        <thead>
        <tr>
            <th>Table</th>
            <th>Séquence</th>
            <th>Séq. val</th>
            <th>Dernier ID</th>
            <th>SQL</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tables as $table => $d): extract($d); ?>
            <tr<?= $probleme ? ' class="bg-danger"' : '' ?>>
                <td><?= $table ?></td>
                <td><?= $sequence ?></td>
                <td><?= $seqVal ?></td>
                <td><?= $maxId ?></td>
                <td>
                    <?php if (!empty($sql)): ?>
                        <button class="btn btn-default btn-maj-seq" data-table="<?= $table ?>">MAJ séquence</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <script>

        $(function ()
        {

            $('.btn-maj-seq').click(function ()
            {
                majSeq($(this).data('table'))
            });

        });

        function majSeq(table)
        {
            var url = '<?= $_SERVER['REQUEST_URI'] ?>';

            $.post(url, {"table-to-modif": table}, function (data)
            {
                alertFlash('Réussi', 'success', 1000);
                window.location.reload();
            }).fail(function (jqXHR)
            {
                alertFlash(jqXHR.responseText, 'danger', 5000);
                console.log(jqXHR);
            });
        }

    </script>
<?php


function getData(Application\Connecteur\Bdd\BddConnecteur $bdd)
{
    $sql = "
    SELECT
      utc.table_name,
      us.sequence_name,
      us.last_number
    FROM
      USER_TAB_COLUMNS utc
      JOIN USER_SEQUENCES us ON us.sequence_name = SUBSTR(utc.table_name,1,23) || '_ID_SEQ'
    WHERE
      utc.column_name = 'ID'
    ";

    $dt = $bdd->fetch($sql, [], 'TABLE_NAME');

    $sqls = [];
    foreach ($dt as $tableName => $d) {
        $sqls[$tableName] = "SELECT '$tableName' table_name, max(id) max_id FROM $tableName";
    }

    $ids = $bdd->fetch(implode(' UNION ', $sqls), [], 'TABLE_NAME');

    $tables = [];
    foreach ($dt as $tableName => $d) {
        $sequence = $d['SEQUENCE_NAME'];
        $seqVal   = (int)$d['LAST_NUMBER'];
        $maxId    = (int)$ids[$tableName]['MAX_ID'];
        $probleme = $seqVal <= $maxId;

        $modifSqls = [];
        if ($maxId - $seqVal >= 0) {
            $modifSqls[] = "ALTER SEQUENCE $sequence INCREMENT BY " . ($maxId - $seqVal + 1);
            $modifSqls[] = "SELECT $sequence.NEXTVAL FROM dual";
            $modifSqls[] = "ALTER SEQUENCE $sequence INCREMENT BY 1";
        }

        $tables[$tableName] = [
            'sequence' => $sequence,
            'seqVal'   => $seqVal,
            'maxId'    => $maxId,
            'probleme' => $probleme,
            'sql'      => $modifSqls,
        ];
    }

    return $tables;
}