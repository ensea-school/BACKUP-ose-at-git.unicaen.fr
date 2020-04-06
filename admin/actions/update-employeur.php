<?php

$fromMaster = true;

$osedir = $oa->getOseDir();
$bdd = $oa->getBdd();

$c->println("Mise à jour de la table employeur");
    $c->print('Préparation du fichier à partir de la source INSEE');
  /*  $c->exec([
        "cd $osedir",
        "cd data/employeurs/sources/INSEE",
        "unzip -o StockUniteLegale_utf8.zip -d ../../extract/",//On dezippe
        "unzip -o StockEtablissement_utf8.zip -d ../../extract/",//On dézippe
        "cd ../../extract/",
        "cut -d \",\" -f 21,1,22,23,24,25,26,27,33 StockUniteLegale_utf8.csv > ../prepare/StockUniteLegale.csv",//On garde uniquement les colonnes nécessaires
        "cd ../prepare",
        "sed -i.bak \"/,C,/d\" StockUniteLegale.csv",//On supprime les unités fermées
        "sed -i.bak \"/,N$/d\" StockUniteLegale.csv",//On supprimer les unités non employeurs
        "cp StockUniteLegale.csv ../import/employeurs-import.csv",//On déplace le fichier dans le dossier d'importation
    ]);*/


$c->println("\nFin du traitement des données employeurs", $c::COLOR_LIGHT_GREEN);


$file = Config::get('employeur', 'import-file');
$nbLigne = shell_exec('wc -l ' . $file);
$c->println("Nombre de ligne à traiter : " . $nbLigne);
$csvFile = fopen($file, r);
$row = 0;
while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {

    $nextId = $bdd->sequenceNextVal('EMPLOYEUR_ID_SEQ');
    $datetime = new \DateTime();
    $date =  $datetime->format('y-m-d h:m:s');
    $sql = "
        INSERT INTO EMPLOYEUR
            (ID,SIREN,LIBELLE, HISTO_CREATEUR_ID, HISTO_CREATION, HISTO_MODIFICATEUR_ID,HISTO_MODIFICATION)
        VALUES ($nextId, '$data[0]', 'Mon entreprise', 1, TO_DATE('2020-04-06 18:03:14', 'YYYY-MM-DD HH24:MI:SS'), 1, TO_DATE('2020-04-06 18:03:14', 'YYYY-MM-DD HH24:MI:SS'))
    ";


    $bdd->exec($sql);

    $employeur = [
      "ID" => $bdd->sequenceNextVal('EMPLOYEUR_ID_SEQ'),
      "LIBELLE" => "puette",
      "SIREN"   => $data[0]
    ];
    $o = ['histo-user-id' => 1];
    $bdd->getTable('EMPLOYEUR')->insert($employeur, $o );
    $row++;
    if($row%1000 == 0)
    {
        $c->println($row);
    }
}
      /*  $sql = "INSERT INTO EMPLOYEUR
               ('ID','LIBELLE', 'SIREN') 
               VALUES ($data[0], $data[2], $data[0]);
        $num = count($data);
        $c->println($num);
        echo "$num champs à la ligne $row : ";
        echo "\n";*/

/*        
                ";*/

$c->println($file);
die;
/*
$data = [];
while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {

    echo "$num champs à la ligne $row : ";
    $row++;
    for ($c=0; $c < $num; $c++) {
        $sql = "INSERT INTO EMPLOYEUR
                        ('ID','LIBELLE', 'SIREN') VALUES ($data[0], $data[2], $data[0]);
                ";
    }
    echo "\n";
}
//$oa->exec('update-employeur');

*/


    /*// Récupération des sources
    $c->println("\nDéploiement à partir des sources GIT", $c::COLOR_LIGHT_CYAN);
    $tbr = $oa->tagIsValid($version) ? 'tags/' : '';
    $c->exec([
        "cd $osedir",
        "git checkout $tbr$version",
        "mkdir cache",
        "chmod 777 cache",
        "mkdir log",
        "chmod 777 log",
        "chmod +7 bin/ose",
    ]);
    $oa->writeVersion($version);
} else {
    $c->exec([
        "cd $osedir",
        "mkdir cache",
        "chmod 777 cache",
        "mkdir log",
        "chmod 777 log",
        "chmod +7 bin/ose",
    ]);
}

try {
    $e              = $c->exec('composer', false, true);
    $composerExists = true;
} catch (\Exception $e) {
    $composerExists = false;
}

if ($composerExists) {
    // Récupération des dépendances
    $c->println("\nChargement des dépendances à l'aide de Composer", $c::COLOR_LIGHT_CYAN);
    $c->passthru("cd $osedir;composer install");
} else {
    // Récupération de Composer
    $c->println("\nRécupération de l'outil de gestion des dépendances Composer", $c::COLOR_LIGHT_CYAN);
    $c->passthru("cd $osedir;wget https://getcomposer.org/composer.phar");

    // Récupération des dépendances
    $c->println("\nChargement des dépendances à l'aide de Composer", $c::COLOR_LIGHT_CYAN);
    $c->passthru("cd $osedir;php composer.phar install");
}

// Mise à jour des liens vers les répertoires publics des dépendances
$oa->run('maj-public-links');

if (!file_exists($osedir . 'config.local.php')) {
    $c->exec([
        "cd $osedir",
        "cp config.local.php.default config.local.php",
    ]);
}

// Génération des proxies pour l'ORM Doctrine
$c->println("\nGénération des proxies pour l'ORM Doctrine", $c::COLOR_LIGHT_CYAN);
$c->exec([
    "cd $osedir",
    "php vendor/bin/doctrine-module orm:generate-proxies",
    "chmod -R 777 cache/DoctrineProxy",
    "chmod -R 777 cache/Doctrine",
]);

// Conclusion
$c->println("\nFin du script d'installation des fichiers", $c::COLOR_LIGHT_GREEN);
$c->println("Il reste encore plusieurs étapes à réaliser pour que OSE soit pleinement fonctionnel :");
$c->println(" 1 - Configurez le cas échéant votre serveur Apache");
$c->println(" 2 - Veuillez personnaliser le fichier de configuration de OSE config.local.php, si ce n'est déjà le cas");
$c->println(" 3 - La base de données devra au besoin être initialisée à l'aide de la commande ./bin/ose install-bdd");
$c->println(" 4 - Mettez en place les tâches CRON nécessaires (envoi de mails pour les indicateurs, Synchronisation automatique, etc.");
$c->println('');
$c->println("Pour la suite, merci de vous reporter au guide de l'administrateur pour vous aider à configurer l'application");
$c->println('');*/