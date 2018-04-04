# Prérequis
## Serveur Web
Installer sur une distribution GNU/Linux - Debian 9 (Stretch) de préférence.

Dépendances requises :

* git
* wget
* Apache 2 avec le module de réécriture d'URL (*rewrite*) activé
* PHP7 avec les modules suivants :
    * cli
    * curl
    * intl
    * json
    * ldap
    * bcmath
    * mbstring
    * mcrypt
    * opcache
    * xml
    * zip
    * OCI8 (pilote pour Oracle).
* unoconv (OSE utilise **UnoConv** pour créer des fichiers PDF à partir de documents au format LibreOffice. **UnoConv** devra donc être installé sur le serveur. Il en existe un paquet intégré à Debian.)

Le mode installation de OSE liste toutes les dépendances nécessaires et teste leur présence sur votre serveur.

## Base de données
Les spécifications sont les suivantes :

* 4 CPU virtuels
* 2 Go de RAM minimum par base de données
* tablespace de 9 Go (pour 3 années d'utilisation)
* un tablespace UNDO de 1 Go minimum ou supprimer la rétention
* un tablespace temporaire de 2 Go minimum
* encodage en UTF-8, Oracle Enterprise Edition 11.2.0.3 (ou +)

# Installation des fichiers

L'installation se fait en récupérant les sources directement depuis le dépôt GitLab de l'Université de Caen.
Un script a été conçu pour automatiser cette opération.

Exécutez la commande suivante sur votre serveur :
```bash
wget https://ose.unicaen.fr/deploiement/ose-deploy && php ose-deploy
```

# Configuration d'Apache
## Avec un VirtualHost
Exemple pris avec /var/www/ose en répertoire d'installation et ose.unicaen.fr en nom d'hôte.
A adapter à vos besoins.
```apache
<VirtualHost *:80>
	ServerName ose.unicaen.fr
	DocumentRoot /var/www/ose/public

	Alias /vendor/unicaen/app /var/www/ose/vendor/unicaen/app/public

	RewriteEngine On
	RewriteBase /

	RewriteCond %{REQUEST_FILENAME} -s [OR]
	RewriteCond %{REQUEST_FILENAME} -l [OR]
	RewriteCond %{REQUEST_FILENAME} -d
	RewriteRule ^.*$ - [NC,L]
	RewriteCond %{REQUEST_URI}::$1 ^(/.+)(.+)::\2$
	RewriteRule ^(.*) - [E=BASE:%1]
	RewriteRule ^(.*)$ %{ENV:BASE}index.php [NC,L]

	# Usage de l'application. 
	# Plusieurs valeurs possibles : development, test, production
	SetEnv APPLICATION_ENV "test"
	php_value upload_max_filesize 50M

	<Directory /var/www/ose/public>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride None
	</Directory>
</VirtualHost>
```

## Avec un alias
Exemple pris avec /var/www/ose en répertoire d'installation et /ose en Alias.
A adapter à vos besoins.
```apache
Alias /ose/vendor/unicaen/app	/var/www/ose/vendor/unicaen/app/public
Alias /ose			/var/www/ose/public

<Directory /var/www/ose/public>
	Options Indexes FollowSymLinks MultiViews
	AllowOverride All
	Order allow,deny
	allow from all

	RewriteEngine On
	RewriteBase /ose

	RewriteCond %{REQUEST_FILENAME} -s [OR]
	RewriteCond %{REQUEST_FILENAME} -l [OR]
	RewriteCond %{REQUEST_FILENAME} -d
	RewriteRule ^.*$ - [NC,L]
	RewriteCond %{REQUEST_URI}::$1 ^(/.+)(.+)::\2$
	RewriteRule ^(.*) - [E=BASE:%1]
	RewriteRule ^(.*)$ %{ENV:BASE}index.php [NC,L]

	# Usage de l'application. 
	# Plusieurs valeurs possibles : development, test, production
	SetEnv APPLICATION_ENV "test"

	php_value upload_max_filesize 50M
</Directory>
```
N'oubliez pas de recharger la configuration d'Apache (systemctl reload apache2)!

# Création de la base de données
Créez une base de données avec un utilisateur pour OSE, un schéma, puis un tablespace vides.
Un script d'initialisation vous est fourni (répertoire data/Déploiement/ose-ddl.sql). Il vous revient de le lancer pour peupler la base de données.

Attention à bien veiller à ce que les accents soient correctement traités.
Les caractères du fichier sont en **UTF8**.

En plus de cale, des Jobs Oracle sont à créer pour effectuer une série de tâches.
Certaines données ont en effet besoin d'être mis à jour périodiquement.
A vous d'adapter les périodicités à vos besoins.

```sql
/

BEGIN
  DBMS_SCHEDULER.CREATE_JOB (
      job_name => 'OSE_CHARGENS_CALCUL_EFFECTIFS',
    job_type => 'STORED_PROCEDURE',
    job_action => 'OSE_CHARGENS.CALC_ALL_EFFECTIFS',
    number_of_arguments => 0,
    start_date => TO_TIMESTAMP_TZ('2017-04-27 17:04:05.788458000 EUROPE/PARIS','YYYY-MM-DD HH24:MI:SS.FF TZR'),
    repeat_interval => 'FREQ=DAILY;BYHOUR=20;BYMINUTE=0;BYSECOND=0',
    end_date => NULL,
    enabled => TRUE,
    auto_drop => FALSE,
    comments => 'Calcul général des effectifs des charges d''enseignement'
  );
END;

/

BEGIN
  DBMS_SCHEDULER.CREATE_JOB (
      job_name => 'OSE_FORMULE_REFRESH',
    job_type => 'STORED_PROCEDURE',
    job_action => 'OSE_FORMULE.CALCULER_TOUT',
    number_of_arguments => 1,
    start_date => TO_TIMESTAMP_TZ('2014-12-09 10:25:17.032495000 EUROPE/PARIS','YYYY-MM-DD HH24:MI:SS.FF TZR'),
    repeat_interval => 'FREQ=DAILY;BYDAY=MON,TUE,WED,THU,FRI,SAT,SUN;BYHOUR=5;BYMINUTE=0;BYSECOND=0',
    end_date => NULL,
    enabled => TRUE,
    auto_drop => FALSE,
    comments => 'Recalcul général de la formule de calcul'
  );
END;

/

BEGIN
  DBMS_SCHEDULER.CREATE_JOB (
      job_name => 'MAJ_ALL_TBL',
    job_type => 'STORED_PROCEDURE',
    job_action => 'OSE_DIVERS.CALCULER_TABLEAUX_BORD',
    number_of_arguments => 0,
    start_date => TO_TIMESTAMP_TZ('2017-11-06 16:03:22.734108000 EUROPE/PARIS','YYYY-MM-DD HH24:MI:SS.FF TZR'),
    repeat_interval => 'FREQ=DAILY;BYHOUR=2,14;BYMINUTE=0;BYSECOND=0',
    end_date => NULL,
    enabled => TRUE,
    auto_drop => FALSE,
    comments => 'Mise à jour de tous les tableaux de bord (hors formule de calcul)'
  );
END;
/
```

# Configuration technique
Personnalisez le fichier `config.local.php` pour adapter OSE à votre établissement.

# Mode installation
Allez ensuite sur OSE. Par défaut, le mode installation est activé.

Ce mode vous permettra de :

*  vérifier que toutes les dépendances nécessaires au bon fonctionnement de l'application sont satisfaites
*  contrôler que les paramètres de configuration que vous avez choisi fonctionnent correctement
*  Choisir ou changer le mot de passe de l'utilisateur `oseappli`, qui est administrateur de l'application.

Une fois cette étape terminée, il convient de passer OSE en mode production. Cela se fait dans le fichier de configuration `config.local.php`, en positionnant à `false` `global/modeInstallation`.

# Mise en place de la tâche CRON
Une tâche CRON doit être lancée sur votre serveur régulièrement.
Elle sert à envoyer les notifications par mail pour les indicateurs à ceux qui se sont abonnés.

En voici la commande :

```cron
###################### 
#         OSE        #
######################
# Notifications par mail des personnes abonnées à des indicateurs.
# Exécution du script du lundi au vendredi,chaque heure de 7h à 1h :
0 5-17 * * 1-5   root    /usr/bin/php /var/www/OSE/bin/ose notifier-indicateurs 1> /tmp/oselog 2>&1
```

OSE est maintenant installé.

Reste ensuite à mettre en place vos connecteurs et à paramétrer l'application en fonction de vos besoins.
Le guide administratif qui vous est fourni vous aidera en cela.
