---
title: "Procédure d'installation de OSE"
author: Laurent Lécluse - DSI - Unicaen
titlepage: true
titlepage-color: 06386e
titlepage-text-color: ffffff
titlepage-rule-color: ffffff
titlepage-rule-height: 1
...

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
    * bcmath
    * gd
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

	Alias /vendor/unicaen/app       /var/www/ose/vendor/unicaen/app/public
	Alias /vendor/unicaen/import    /var/www/ose/vendor/unicaen/import/public

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
Alias /ose/vendor/unicaen/app	    /var/www/ose/vendor/unicaen/app/public
Alias /ose/vendor/unicaen/import	/var/www/ose/vendor/unicaen/import/public
Alias /ose			                /var/www/ose/public

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

# Configuration technique
Personnalisez le fichier `config.local.php` pour adapter OSE à votre établissement.

# Mode installation
Allez ensuite sur OSE. Par défaut, le mode installation est activé.

Ce mode vous permettra de :

*  vérifier que toutes les dépendances nécessaires au bon fonctionnement de l'application sont satisfaites
*  contrôler que les paramètres de configuration que vous avez choisi fonctionnent correctement
*  Choisir ou changer le mot de passe de l'utilisateur `oseappli`, qui est administrateur de l'application.

Une fois cette étape terminée, il convient de passer OSE en mode production. Cela se fait dans le fichier de configuration `config.local.php`, en positionnant à `false` `global/modeInstallation`.

# Mise en place des tâches CRON
Des tâches CRON doivent être lancée sur votre serveur régulièrement pour mettre à jour certaines données
ou réaliser des actions.

Ces tâches n'ont pas besoin d'être lancées régulièrement sur un serveur de pré-production.

Dans tous les cas, c'est le script de OSE qui sera appelé.
Le script est situé dans le répertoire de OSE, `bin/ose`.
Il est suivi de l'action à exécuter, puis éventuellement de paramètres à préciser.

Exemple d'utilisation pour lancer une tâche de synchronisation appelée `synchro`:
```bash
/usr/bin/php /var/www/ose/bin/ose synchronisation synchro
```

| Usage                 | Fréquence             | Action de script      |
| --------------------- | --------------------- | --------------------- |
| Indicateurs : envoi des notifications par mail | Les jours de semaine entre 5h et 17h | notifier-indicateurs |
| Synchronisation : Mise en place d'un job pour l'import des données. Plusieurs jobs pourront être créés au besoin | Tous les quarts d'heures entre 7h et 21h sauf le dimanche | synchronisation `<Nom du job>` |  
| Calcul des effectifs du module Charges | une fois par jour, à 20h tous les jours sauf le dimanche. | chargens-calcul-effectifs |
| Calcul des tableaux de bord | Deux fois par jour sauf le dimanche (Calcul LONG) | calcul-tableaux-bord |
| Calcul des heures complémentaires à l'aide de la formule (calcul LONG) | Les lundi et jeudi à 3h | formule-calcul |

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
```

OSE est maintenant installé.

Reste ensuite à mettre en place vos connecteurs et à paramétrer l'application en fonction de vos besoins.
Le guide administratif qui vous est fourni vous aidera en cela.
