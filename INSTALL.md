# Généralités

OSE est une application web qui exploite une base de données Oracle.
Il faut donc installer :

* Une base de données Oracle
* Un serveur web Apache + PHP

Le serveur web doit être installé par vos soins.
Le serveur web n'héberge aucune donnée, hormis des fichiers de configuration et de cache. Toutes les données d'explloitation sont donc
stockées en base de données.

# Serveur de base de donnée

### Spécifications
Les spécifications sont les suivantes :

* 4 CPU virtuels
* 2 Go de RAM minimum par base de données
* tablespace de 9 Go (pour 3 années d'utilisation)
* un tablespace UNDO de 1 Go minimum ou supprimer la rétention
* un tablespace temporaire de 2 Go minimum
* encodage en UTF-8, Oracle 19 minimum, XE ou entreprise

### Paramétrage
Pour information, notre base de données est configurée avec les paramètres suivants :

| Paramètre             | Valeur      |
| --------------------- | ----------- |
|NLS_LANGUAGE | AMERICAN |
|NLS_TERRITORY | AMERICA |
|NLS_CURRENCY | $ |
|NLS_ISO_CURRENCY | AMERICA |
|NLS_NUMERIC_CHARACTERS | ., |
|NLS_CHARACTERSET | AL32UTF8 |
|NLS_CALENDAR | GREGORIAN |
|NLS_DATE_FORMAT | DD-MON-RR |
|NLS_DATE_LANGUAGE | AMERICAN |
|NLS_SORT | BINARY |
|NLS_TIME_FORMAT | HH.MI.SSXFF AM |
|NLS_TIMESTAMP_FORMAT | DD-MON-RR HH.MI.SSXFF AM |
|NLS_TIME_TZ_FORMAT | HH.MI.SSXFF AM TZR |
|NLS_TIMESTAMP_TZ_FORMAT | DD-MON-RR HH.MI.SSXFF AM TZR |
|NLS_DUAL_CURRENCY | $ |
|NLS_COMP | BINARY |
|NLS_LENGTH_SEMANTICS | BYTE |
|NLS_NCHAR_CONV_EXCP | FALSE |
|NLS_NCHAR_CHARACTERSET | AL16UTF16 |
|NLS_RDBMS_VERSION | 11.2.0.3.0 |

La liste de vos paramètres est accessible via la requête suivante :
```sql
SELECT * FROM NLS_DATABASE_PARAMETERS;
```

### Afficher les messages d'erreur en français dans OSE
Pour afficher les messages d'erreur en français dans OSE, il faut définir une variable d'environnement PHP
spécifique : `NLS_LANG` avec pour valeur `FRENCH`.
 
Avec Apache, vous devez ajouter la ligne suivante à votre configuration dans le fichier envvars :
```apache
export NLS_LANG="FRENCH"
```

# Installation du serveur web

Le serveur Web doit mainternant être installé.
Il vous revient de réaliser cette opération en vous basant sur la version fournie par votre système d'exploitation.
OSE n'impose pas de version bien spécifique d'Apache.

## Méthode manuelle, à adapter selon vos besoins

### Serveur Web
Installer sur une distribution GNU/Linux - Debian 10 (Buster) de préférence.

Dépendances requises :

* git
* wget
* Apache 2 avec le module de réécriture d'URL (*rewrite*) activé
* PHP 8.2 avec les modules suivants :
    * cli
    * curl
    * intl
    * json
    * ldap
    * bcmath
    * mbstring
    * opcache
    * xml
    * zip
    * bcmath
    * gd
    * soap
    * OCI8 (version 3.3.0 / pilote pour Oracle).

Le mode installation de OSE liste toutes les dépendances nécessaires et teste leur présence sur votre serveur.

### Unoconv
#### Présentation
Unoconv est un utilitaire qui utilise LibreOffice pour convertir des documents depuis le format OpenDocument
 vers le format PDF.

OSE l'utilise par exemple pour générer des contrats de travail ou d'autres états de sortie destinés à l'impression.

**UnoConv** devra donc être installé sur le serveur. 

#### Installation 
Unoconv est intégré à la plupart des distributions GNU/Linux.

Spécifique à Debian :
Il en existe un paquet intégré à Debian.)
Attention également : sous Debian, unoconv peut s'installer par apt-get, mais il faut aussi installer LibreOffice Writer, la dépendance n'étant pas automatique (`apt-get install unoconv libreoffice-writer`).

#### Configuration
Unoconv doit être lancé en tant que démon.
Il tournera donc en tâche de fond et OSE fera appel à lui pour effectuer les conversions en PDF souhaitées.
Pour cela, la commande `unoconv --listener` doit être lancée.

Voici un exemple de configuration du démon unoconv lancé au moyen de systemd :

Dans le fichier `/etc/systemd/system/unoconv.service`, placez le contenu suivant :

```ini
[Unit]
Description=Unoconv listener for document conversions
Documentation=https://github.com/dagwieers/unoconv
After=network.target remote-fs.target nss-lookup.target
 
[Service]
Type=simple
ExecStart=/usr/bin/unoconv --listener
 
[Install]
WantedBy=multi-user.target
```

Puis activez et lancez le service :

```bash
systemctl enable unoconv.service
systemctl start unoconv.service
```


### Fichiers de l'application

L'installation se fait en récupérant les sources directement depuis le dépôt GitLab de l'Université de Caen.
Un script a été conçu pour automatiser cette opération.

Pour installer OSE, exécutez la commande suivante sur votre serveur :
```bash
wget https://ose.unicaen.fr/install && php install
```


### Configuration d'Apache
#### Exemple avec un VirtualHost
Exemple pris avec /var/www/ose en répertoire d'installation et ose.unicaen.fr en nom d'hôte.
A adapter à vos besoins.
```apache
<VirtualHost *:80>
	ServerName ose.unicaen.fr
	DocumentRoot /var/www/ose/public

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
	# Plusieurs valeurs possibles : dev (pour le développement), test (pour du test), prod (à utiliser en production)
	SetEnv APPLICATION_ENV "test"
	php_value upload_max_filesize 50M
	php_value post_max_size 100M
	php_value max_execution_time 300
	php_value max_input_time 60
	php_value memory_limit 1024M

	<Directory /var/www/ose/public>
		Options Indexes MultiViews
		AllowOverride All
	</Directory>
</VirtualHost>
```

#### Exemple avec un alias
Exemple pris avec /var/www/ose en répertoire d'installation et /ose en Alias.
A adapter à vos besoins.
```apache
Alias /ose			                /var/www/ose/public

<Directory /var/www/ose/public>
	Options Indexes MultiViews
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
	# Plusieurs valeurs possibles : dev (pour le développement), test (pour du test), prod (à utiliser en production)
	SetEnv APPLICATION_ENV "test"

	php_value upload_max_filesize 50M
	php_value post_max_size 100M
	php_value max_execution_time 300
	php_value max_input_time 60
	php_value memory_limit 1024M
</Directory>
```
N'oubliez pas de recharger la configuration d'Apache (systemctl reload apache2)!


# Configuration technique
Personnalisez le fichier `config.local.php` pour adapter OSE à votre établissement.
Une attention toute particulière doit être prise pour configurer les paramètres de base de données, car ces derniers
seront utiles pour terminer la procédure d'installation.

# Création de la base de données
Créez une base de données avec un utilisateur pour OSE, un schéma, puis un tablespace vides.

Les droits de l'utilisateur Ose doivent être les suivants :

```sql
GRANT "CONNECT" TO "OSE";
GRANT "RESOURCE" TO "OSE";
GRANT "SELECT_CATALOG_ROLE" TO "OSE";
GRANT CREATE JOB TO "OSE";
GRANT FLASHBACK ANY TABLE TO "OSE";
GRANT DEBUG ANY PROCEDURE TO "OSE";
GRANT DEBUG CONNECT SESSION TO "OSE";
GRANT SELECT ANY DICTIONARY TO "OSE";
GRANT ON COMMIT REFRESH TO "OSE";
GRANT CREATE MATERIALIZED VIEW TO "OSE";
GRANT CREATE DATABASE LINK TO "OSE";
GRANT CREATE VIEW TO "OSE";
GRANT DROP PUBLIC SYNONYM TO "OSE";
GRANT CREATE PUBLIC SYNONYM TO "OSE";
GRANT UNLIMITED TABLESPACE TO "OSE";
GRANT ALTER SESSION TO "OSE";
GRANT SELECT ON "SYS"."DBA_ROLE_PRIVS" TO "OSE";
GRANT SELECT ON "SYS"."DBA_TAB_PRIVS" TO "OSE";
GRANT SELECT ON "SYS"."DBA_ROLES" TO "OSE";
GRANT FLASHBACK ON "SYS"."ALL_SOURCE" TO "OSE";
GRANT EXECUTE ON "SYS"."DBMS_LOCK" TO "OSE";
GRANT EXECUTE ON "SYS"."DBMS_ALERT" TO "OSE";
```

Une fois la base de données créée, il faut mettre en place les tables, les vues, etc.
Cela se fait au moyen de la commande suivante (depuis le répertoire de OSE) :

```bash
./bin/ose install-bdd
``` 

# Mise en place des tâches CRON
Des tâches CRON doivent être lancée sur votre serveur régulièrement pour mettre à jour certaines données
ou réaliser des actions. 

Ceci est utile en production. Il n'est pas conseillé d'activer ces tâches CRON en développement ou
en test, mais plutôt de lancer manuellement ces tâches.

Dans tous les cas, c'est le script de OSE qui sera appelé.
Le script est situé dans le répertoire de OSE, `bin/ose`.
Il est suivi de l'action à exécuter, puis éventuellement de paramètres à préciser.

Exemple d'utilisation pour lancer une tâche de synchronisation appelée `synchro`:
```bash
/usr/bin/php [votre dossier ose]/bin/ose synchronisation synchro
```

| Usage                 | Fréquence             | Action de script      |
| --------------------- | --------------------- | --------------------- |
| Indicateurs : envoi des notifications par mail                         | Les jours de semaine entre 5h et 17h              | notifier-indicateurs |
| Synchronisation : Mise en place d'un job pour l'import des données. Plusieurs jobs pourront être créés au besoin           | Tous les quarts d'heures entre 7h et 21h sauf le dimanche | synchronisation `<Nom du job>` |  
| Calcul des effectifs du module Charges                                 | Une fois par jour, à 20h tous les jours sauf le dimanche. | chargens-calcul-effectifs |
| Calcul des tableaux de bord                                            | Deux fois par jour sauf le dimanche (Calcul LONG) | calcul-tableaux-bord |
| Calcul des heures complémentaires à l'aide de la formule (calcul LONG) | Les lundi et jeudi à 3h                           | formule-calcul |
| MAJ des taux de mixité à partir des effectifs de l'année courante      | Tous les 15 décembre à 7h                         | maj-taux-mixite |
| MAJ des vues matérialisées dédiées aux exports (BO, SID, etc.)         | Une fois par jour, à 4h tous les jours sauf le dimanche | maj-exports |

Après la commande, on ajoute `> /tmp/oselog 2>&1` pour loguer le résultat dans le fichier`/tmp/oselog`.
A adapter le cas échéant.

Voici un exemple de crontab :

```cron
# m  h    dom mon dow command
0    5-17 *   *   1-5 /usr/bin/php /var/www/ose/bin/ose notifier-indicateurs      > /tmp/oselog 2>&1
*/15 7-21 *   *   1-6 /usr/bin/php /var/www/ose/bin/ose synchronisation synchro   > /tmp/oselog 2>&1
0      20 *   *   1-6 /usr/bin/php /var/www/ose/bin/ose chargens-calcul-effectifs > /tmp/oselog 2>&1
0    6,14 *   *   1-6 /usr/bin/php /var/www/ose/bin/ose calcul-tableaux-bord      > /tmp/oselog 2>&1
0       3 *   *   1,4 /usr/bin/php /var/www/ose/bin/ose formule-calcul            > /tmp/oselog 2>&1
0      7 15  12     * /usr/bin/php /var/www/ose/bin/ose maj-taux-mixite           > /tmp/oselog 2>&1
0       4 *   *   1-6 /usr/bin/php /var/www/ose/bin/ose maj-exports               > /tmp/oselog 2>&1
```

OSE est maintenant installé.

## Cas spécifique des versions de pré-production

Il n'est pas recommandé d'activer les notifications pour les indicateurs en pré-production.
De même, il est déconseillé de lancer la tâche de synchronisation tous les quarts d'heure en pré-prod.
Le mieux est, dans ce contexte, de réaliser ces synchronisations manuellement, table par table, depuis l'interface de OSE.
cela vous permettra de

*  mieux maitriser les flux de données entre votre SI et OSE ;
*  mieux identifier des problèmes éventuels au niveau des connecteurs puisque OSE affiche les erreurs rencontrées à l'écran ;
*  d'avoir une offre de formation de test "stable", c'est-à-dire qui ne se met pas à jour toute seule, ce qui simplifiera vos tests.

En revanche, il est vivement recommandé de bien paramétrer en préproduction les autres tâches
(calcul des effectifs Chargens, calcul des tableau de bord, formule de calcul).


# Connecteurs
Afin de pouvoir intégrer OSE à votre système d'information, 
des [connecteurs Import](doc/Connecteurs-Import/Connecteurs-IMPORT.md) 
vous sont fournis à titre d'exemple.
Vous devrez en effet les adapter à vos besoins.

# Paramétrage de l'application

Reste enfin à paramétrer l'application en fonction de vos besoins.
[Le guide administratif](https://redmine.unicaen.fr/Etablissement/projects/deploiement_ose/dmsf?folder_id=3204) vous aidera en cela.
