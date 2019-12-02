<?php
// Assume that all external field ended by _ID
use UnicaenCode\Form\ElementMaker;
use UnicaenCode\Util;

/**
 * @var $this       \Zend\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 */

?>
    <h1>Création d'une rubrique dans un menu</h1>
    <h3>Etape 1 : Paramétrage</h3>

<?php

$form = new \Zend\Form\Form();
$em = $controller->getServiceLocator()->get('Doctrine\ORM\EntityManager');
$conn = $em->getConnection();
$sm = $conn->getSchemaManager();
$tables = $sm->listTableNames();
sort($tables);
$val = [];
$form->add([
    'type' => 'Zend\Form\Element\Select',
    'name' => 'tables',
    'options' => [
        'label' => 'Tables',
    ],
]);

foreach ($tables as $tbl) {
    $val[$tbl] = $tbl;
}
$form->get('tables')->setValueOptions($val);

$form->add([
    'type' => 'Zend\Form\Element\Select',
    'name' => 'menus',
    'options' => [
        'label' => 'Menu',
        'value_options' => [
            'Administration' => 'Administration',
            'Gestion' => 'Gestion',
        ],
    ],
]);

$form->add([
    'type' => 'Zend\Form\Element\Text',
    'name' => 'Origine',
    'options' => [
        'label' => 'Rep Web',
    ]
]);
$form->get('Origine')->setValue('/var/www/OSE/');

$form->add(ElementMaker::checkbox(
    'classe-privilege', 'Privilège existant ?', true
));

$form->add(ElementMaker::checkbox(
    'non-remplacement', 'Ne pas générer fichier déjà existant ?', true
));

$form->add(ElementMaker::submit('generate', 'Générer le code'));

$form->setData($controller->getRequest()->getPost());

Util::displayForm($form);

if (($controller->getRequest()->isPost()) && ($form->isValid())) {
    $baseOrig = $form->get('Origine')->getValue() . 'module/Application/';
    $nom = $form->get('tables')->getValue();
    $basefs = 'module/Application/';
    $nomsous = strtoupper($nom);
    $nomvisu = $nomsous . "_VISUALISATION";
    $nomedit = $nomsous . "_EDITION";
    if (!$form->get('classe-privilege')->getValue()) {
        $nomvisu = 'DROIT_PRIVILEGE_EDITION';
        $nomedit = 'DROIT_PRIVILEGE_VISUALISATION';
    }
    $nonRemplace = $form->get('non-remplacement')->getValue();
    $nomtiret = $nomm = strtolower($nom);
    $nomtiret = str_replace('_', '-', $nomtiret);
    $nomm = preg_replace_callback('/_\w/', function ($m) {
        return strtoupper($m[0]);
    },
        $nomm);
    $nommaj = preg_replace_callback('/^\w/', function ($m) {
        return strtoupper($m[0]);
    }, $nomm);
    $nomphrase = str_replace('_',' ', $nommaj);
    $nomm = str_replace('_','', $nomm);
    $nommaj = str_replace('_','', $nommaj);

    /**
     * $col Doctrine\DBAL\Schema\Column
     */
    $nomChamps = [];
    $typeChamps = [];
    $lgChamps = [];
    $listeChamps = [];
    $notnullChamps = [];
    $defaultChamps = [];
    $result = []; // résultat d'exec

    $nbe = 0;
    if (empty($nom)) die('');

    $columns = $sm->listTableColumns($nom);
    foreach ($columns as $col) {
        $nchp = $col->getName();
        $notnullChamps[$nbe] = $col->getNotnull();
        $defaultChamps[$nbe] = $col->getDefault();
        if (!preg_match('/^HISTO_/', $nchp)) {
            $nomChamps[$nbe] = $nchp;
            if (preg_match('/_ID$/', $nchp)) {
                $listeChamps[$nbe] = $nomChamps[$nbe] = preg_replace('/_ID$/', '', $nchp);
                if (preg_match('/^ANNEE_/', $nchp)) $listeChamps[$nbe] = 'ANNEE';
            } else $listeChamps[$nbe] = '';
            $typeChamps[$nbe] = $col->getType()->getName();
            $lgChamps[$nbe] = $col->getLength();
            if (($typeChamps[$nbe] == 'integer') || ($typeChamps[$nbe] == 'float')) {
                $lgChamps[$nbe] = $col->getPrecision();
                if ($lgChamps[$nbe] == 1) $typeChamps[$nbe] = 'boolean';
            }
            $nbe++;
        }
    }
 //   if (file_exists('/tmp')) {
    //       exec("rm -rf /tmp", $stdout, $err);
//    }
//    mkdir('/tmp', 0770) or die('Vous devez créer un sous répertoire /var/www/Web avec les droits d\'écriture! pour l\'utilisateur www-data<BR>');
//echo "res:$res<BR>";
// mise en place des champs
    $txt0 = $txt01 = $txt4 = $txt41 = $txt42 = $txt43 = $txt44 = $txt45 = $txt5 = $txt53 = $txt6 = $txt7 = '';

// chargement des pseudos fichiers
    $tblInclude = array(
        'champInput', 'champListe', 'config_php', 'controller_php', 'idx1', 'idx3', 'idx20', 'saisie_phtml',
        'saisieFormAwareTrait_php', 'service_php', 'serviceTrait_php'
    );

    $i = 0;
    foreach ($tblInclude as $ci) {
        ${$ci} = file_get_contents(__DIR__ . '/template/GenerateRubrique/' . $ci);
    }

    $extractInput = '\'champtiret\'       => $object->getchampmaj()
    ,';

    $extractListe = '\'champtiret\'       => ($s = $object->getchampmaj()) ? $s->getId() : null,
    ';

    $hydrateInput = '$object->setchampmaj($data[\'champtiret\']);
    ';

    $hydrateListe = 'if (array_key_exists(\'champtiret\', $data)) {
            $object->setchampmaj($this->getServicechampmaj()->get($data[\'champtiret\']));
        }
    ';

    $idx21 = "                    <th style=\"word-wrap: break-word ; \">champphrase</th>\n";
    $idx2 = '                     <td style="word-wrap: break-word ; "><?= $fr->getchampmaj() ?></td>' . "\n";
    $service10 = "    use Application\Service\Traits\champmajServiceAwareTrait;\n";
    $service11 = "        use champmajServiceAwareTrait;\n";


    $fic10 = '/**
 * Description of nommajSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class nommajSaisieForm extends AbstractForm
{
    ';

    $fic11 = '
    

 
    public function init()
    {
        $hydrator = new nommajHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute(\'action\', $this->getCurrentUrl());
    ';

    $fic1 = '<?php

namespace Application\Form\nommaj;

use Application\Form\AbstractForm;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
    ';

    $fic2 = '
            $this->add(new Csrf(\'security\'));
        $this->add([
            \'name\'       => \'submit\',
            \'type\'       => \'Submit\',
            \'attributes\' => [
                \'value\' => "Enregistrer",
                \'class\' => \'btn btn-primary\',
            ],
        ]);
 
	return $this;
    }


    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [';
    $fic21 = '
        ];
    }

}





class nommajHydrator implements HydratorInterface
{
';
    $listeEnteteHydrator = 'use champmajServiceAwareTrait;
';

    $fic22 = '
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\nommaj $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
    ';

    $inputFilter = '
        \'champtiret\'                => [
                \'required\' => true,
        ],
';

    $fic3 = '
            return $object;
    }
            

            
    /**         
     * Extract values from an object
     *      
     * @param  \Application\Entity\Db\nommaj $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
    ';

    $fic4 = '
            ];

        return $data;
    }
}   
    ';

    for ($i = 0; $i < $nbe; $i++) {
        $champ = $nomChamps[$i];
        $champsous = strtoupper($champ);
        $champtiret = $champm = strtolower($champ);
        $champtiret = str_replace('_', '-', $champtiret);
        $champm = preg_replace_callback('/_\w/', function ($m) {
            return strtoupper($m[0]);
        },
            $champm);
        $champmaj = preg_replace_callback('/^\w/', function ($m) {
            return strtoupper($m[0]);
        }, $champm);
        $champphrase = str_replace('_', ' ', $champmaj);
        $champm = str_replace('_', '', $champm);
        $champmaj = str_replace('_', '', $champmaj);

        # input:champInput.txt listeInput.txt ...
        if (($i) && ($notnullChamps[$i])) {
            $txt = str_replace('champtiret', $champtiret, $inputFilter);
            $txt6 .= $txt;
        }
        if ($champtiret != 'id') {
            $txt52 = str_replace('champphrase', $champphrase, $idx21);
            $txt53 .= $txt52;
        }
        if (!$listeChamps[$i]) {
            $champType = 'Text';
            if ($typeChamps[$i] == 'String') $champType = 'Text';
            if ($typeChamps[$i] == 'boolean') $champType = 'Checkbox';
            if ($champtiret != 'id') {
                $txt51 = str_replace('champmaj', $champmaj, $idx2);
                $txt5 .= $txt51;
            }
            if ($champtiret != 'id') {
                $txt = str_replace(['champtiret', 'champphrase', $champType], [$champtiret, $champphrase, $champType], $champInput);
                $txt4 .= $txt;
            }
            if ($champtiret != 'id') {
                $txt = str_replace('champtiret', $champtiret, $hydrateInput);
                $txt = str_replace('champmaj', $champmaj, $txt);
                $txt41 .= $txt;
            }
            $txt = str_replace('champtiret', $champtiret, $extractInput);
            $txt = str_replace('champmaj', $champmaj, $txt);
            $txt42 .= $txt;
        } else {
            $txt51 = str_replace('champmaj', $champmaj, $idx2);
            $txt52 = str_replace('champphrase', $champphrase, $idx21);
            $txt5 .= $txt51;
            $txt52 .= $txt53;
            $txt = str_replace('champmaj', $champmaj, $service10);
            $txt0 .= $txt;
            $txt = str_replace('champmaj', $champmaj, $service11);
            $txt01 .= $txt;
            $txt = str_replace(['champtiret', 'champphrase', 'champmaj'], [$champtiret, $champphrase, $champmaj], $champListe);
            if ($notnullChamps[$i]) {
                $txt = str_replace('            ->setEmptyOption("(Aucun)")', '', $txt);
            }
            $txt43 .= $txt;
            $txt = str_replace(['champtiret', 'champmaj'], [$champtiret, $champmaj], $hydrateListe);
            $txt44 .= $txt;
            $txt = str_replace(['champtiret', 'champmaj'], [$champtiret, $champmaj], $extractListe);
            $txt45 .= $txt;
            $txt = str_replace('champmaj', $champmaj, $listeEnteteHydrator);
            $txt7 .= $txt;
        }
    }

    $saisieForm_php = $fic1 . $txt0 . $fic10 . $txt01 . $fic11 . $txt4 . $txt43 . $fic2 . $txt6 . $fic21 . $txt7 . $fic22 . $txt41 . $txt44 . $fic3 . $txt42 . $txt45 . $fic4;
    $index_phtml = $idx1 . $txt53 . $idx20 . $txt5 . $idx3;

    $reps = ['config/',
        'src/Application/Controller/',
        'src/Application/Form/',
        'src/Application/Form/',
        'view/application/',
        'view/application/',
        'src/Application/Service/',
        'src/Application/Service/Traits/'];

    $rep2regs = ['',
        '',
        $nommaj . '/',
        $nommaj . '/Traits/',
        $nomtiret . '/',
        $nomtiret . '/',
        '',
        ''];

    $fichmotifs = [$config_php,
        $controller_php,
        $saisieForm_php,
        $saisieFormAwareTrait_php,
        $index_phtml,
        $saisie_phtml,
        $service_php,
        $serviceTrait_php];

    $fichiers = [$nomtiret . '.config.php',
        $nommaj . 'Controller.php',
        $nommaj . 'SaisieForm.php',
        $nommaj . 'SaisieFormAwareTrait.php',
        'index.phtml', 'saisie.phtml',
        $nommaj . 'Service.php',
        $nommaj . 'ServiceAwareTrait.php'];
    $motif = ['nomsous', 'nommaj', 'nomm', 'nomtiret', 'droitedition', 'droitvisualisation', 'nomphrase'];
    $remplace = [$nomsous, $nommaj, $nomm, $nomtiret, $nomedit, $nomvisu, $nomphrase];
    $maxfichiers = sizeof($fichiers);
    for ($i = 0; $i < $maxfichiers; $i++) {
        $rep = $reps[$i];
        $fich = $fichiers[$i];
        $fmotif = $fichmotifs[$i];
        $rep2reg = $rep2regs[$i];
        $ficin = $fichmotifs[$i];

        $ficout = $basefs . $rep . $rep2reg . $fich;
        $ficenr = $baseOrig . $rep . $rep2reg . $fich;
//    echo 'Ficout=' . $ficout.', ficenr:'.$ficenr.'<br>';
        $txt = str_replace($motif, $remplace, $ficin);
        if (!file_exists($basefs . $rep . $rep2reg)) {
            $chemin = $basefs . $rep . $rep2reg;
            $res = exec("mkdir -p $chemin", $err);
//        echo "res:$res<BR>";
        }
        //$hdl = fopen($ficout, 'wb');
        echo '<BR><BR>'.$ficout.'<BR>';
        echo str_replace(array(" ","<",">","\"","\n"),array('&nbsp;','&lt;','&gt;','&quot;','<br>'),$txt);
        // $lgWrite = fwrite($hdl, $txt);
        // fclose($hdl);
        if (file_exists($ficenr)) {
            if ($nonRemplace) {
                unset($result);
                exec("diff $ficenr $ficout", $result);
                echo "<BR><span style=\"color:#00bbdd; \">Différences avec $ficenr<BR><HR></span>";
                echo '<div style="position:relative; left:10%; font-family:Lucida Console; background-color:lightgrey; width:85% ">';
                $nblg = sizeof($result);
                for ($j = 0; $j < $nblg; $j++) {
                    echo '<span style="color:';
                    if (preg_match('/^\d/', $result[$j])) echo '#008bb9';
                    if (preg_match('/^>/', $result[$j])) echo '#000000';
                    if (preg_match('/^</', $result[$j])) echo '#00bb00';
                    if (preg_match('/^---/', $result[$j])) {
                        echo '; width:500px; "><hr width="85%" size="2"></span>';
                    } else echo "; \"><BR>$result[$j]</span>";
                }
                echo '</div>';
                unlink($ficout);
            } else echo '<div style="color:#ff0000; ">Attention --> Le fichier module/Application/' . $ficout . ' existe dans /var/www/OSE !</div>';
        }
    }

}
