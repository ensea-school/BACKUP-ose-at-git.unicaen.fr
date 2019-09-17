<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Interop\Container\ContainerInterface
 */


use UnicaenApp\Service\EntityManagerAwareTrait;

$view   = 'v_tbl_chargens';
$tblKey = 'crg';





class Lcv
{
    use EntityManagerAwareTrait;

    /**
     * @var string
     */
    protected $view;



    public function __construct($view = null)
    {
        $this->view = $view;
    }



    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }



    /**
     * @param string $view
     *
     * @return Lcv
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }



    public function getViewDefinition()
    {
        $sql    = "SELECT TEXT FROM USER_VIEWS WHERE VIEW_NAME = '" . strtoupper($this->getView()) . "'";
        $result = $this->getEntityManager()->getConnection()->fetchAll($sql);

        return $result[0]['TEXT'];
    }



    public function getViewDepTables()
    {
        $sql = "SELECT referenced_name FROM user_dependencies WHERE name = '" . strtoupper($this->getView()) . "'";

        $result = $this->getEntityManager()->getConnection()->fetchAll($sql);
        $tables = [];

        foreach ($result as $r) {
            $tables[] = $r['REFERENCED_NAME'];
        }
        sort($tables);

        return $tables;
    }



    public function getViewDepColumns()
    {
        $q    = $this->getViewDefinition();
        $tbls = $this->getViewDepTables();

        $q = strtoupper($q);
        $q = preg_replace('/\s{2,}/', ' ', $q);
        $q = str_replace(['(', ')', "\t", "\n", '*', '+', ',', '-'], [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '], $q);
        $q = explode(' ', $q);

        $struct = [];
        foreach ($tbls as $table) {
            foreach ($q as $i => $w) {
                if ($w == $table) {
                    $ai = $i + 1;
                    if ($q[$ai] == 'AS') $ai++;
                    $struct[$q[$ai]] = ['table' => $table, 'columns' => []];
                }
            }
        }

        foreach ($struct as $alias => $s) {
            foreach ($q as $i => $w) {
                if (0 === strpos($w, $alias . '.')) {
                    $struct[$alias]['columns'][$w] = substr($w, strlen($alias) + 1);
                }
            }
        }

        $columns = [];

        foreach ($struct as $alias => $s) {
            foreach ($s['columns'] as $col) {
                $columns[$s['table']][$col] = $col;
            }
        }

        foreach ($columns as $table => $cols) {
            sort($columns[$table]);
        }

        return $columns;
    }



    public function generateTblTriggers($tblKey = 'TBL')
    {
        $d = $this->getViewDepColumns();

        $triggers = '';

        foreach ($d as $table => $cols) {
            foreach ($cols as $i => $c) {
                if ($c == 'ID') {
                    unset($cols[$i]);
                }
            }

            $sql = 'CREATE OR REPLACE TRIGGER T_' . strtoupper($tblKey) . '_' . $table . '
AFTER 
  INSERT 
  OR UPDATE OF ' . implode(', ', $cols) . ' 
  OR DELETE ON ' . $table . '
FOR EACH ROW
BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  /* REMPLIR ICI */

END;

/

';

            $triggers .= $sql;
        }


        return $triggers;
    }
}





$lcv = new Lcv;
$lcv->setEntityManager($sl->get('Doctrine\ORM\EntityManager'));
$lcv->setView($view);

echo '<pre>' . $lcv->generateTblTriggers($tblKey) . '</pre>';
