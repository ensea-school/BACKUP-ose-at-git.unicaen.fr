# Principe

Depuis la version 24 de OSE, il est maintenant possible de paramétrer la signature électronique pour les contrats et
avenant au sein de l'application.

Actuellement, seul le parafeur numérique ESUP SIGNATURE est disponible dans l'application. L'objectif étant d'étendre
cette fonctionnalité à d'autres outils de signature électronique utilisés par la communauté OSE.

# Configuration de ESUP SIGNATURE

Il vous faut ajouter un certains nombres de nouveaux paramètres dans votre fichier de config.local.php. Vous trouverez
un exemple des paramètres attendus dans le fichier config.local.php.dist version sur le gitlab.

Voici les paramètres à mettre en place  :

```php
   'unicaen-signature' => [
        //Répertoir de travail permettant de générer et/ou récupérer physiquement les document au format pdf pour les envoyer par la suite dans le parafeur    
        'documents_path' => __DIR__ . '/data/signature',

        //Paramétrage des logs concernant la signature. Permet d'avoir un certain nomnbre de trace sur le fonctionnement de la signature électronique
        'logger' => [
            'enable'          => true,
            'level'           => \Monolog\Logger::DEBUG,
            'file'            => __DIR__ . '/cache/unicaen-signature.log',
            'stdout'          => false,
            'file_permission' => 0666,
            'customLogger'    => null,

        ],

        //Cette partie permet de surcharger les personnes à qui seront envoyées les signatures électronique. L'envoi des mail étant géré directement par le parafeur
        //Cela permet de jouer des circuits de signature sans que les emails partent aux personnes concernées
        'hook_recipients' => [
                ['firstname' => 'Jean',
                 'lastname'  => 'Dupont',
                 'email'     => 'jean.dupont@universite.fr',],
                ['firstname' => 'Jean',
                 'lastname'  => 'Dupont',
                 'email'     => 'jean.dupont@universite.fr'],
            ],
        //Configuration du parafeur à utiliser, ici la configuration Esup uniquement disponible pour le moment.
        'letterfiles' =>
            [
                [

                    'label'       => 'ESUP signature',
                    'name'        => 'esup',
                    'default'     => true,
                    'class'       => \UnicaenSignature\Strategy\Letterfile\Esup\EsupLetterfileStrategy::class,
                    'description' => 'Esup',
                    //Les différents niveaux de signature à activer au sein de OSE
                    'levels'      => [
                        'visa_hidden' => 'hiddenVisa',
                        'visa_visual' => 'visa',
                        'sign_visual' => 'pdfImageStamp',
                        'sign_certif' => 'certSign',
                        'sign_eidas'  => 'nexuSign',

                    ],
                    
                    'config'      => [
                        // Url pour les webservices de esup
                        'url'           => "https://signature.etablissement.fr",
                        //L'utilisateur qui sera utiliser pour créer les demandes de signature dans esup
                        'createdByEppn' => 'xxxxxxxxxxx',
                    ],
                ],
            ],
        //Nécessaire pour le bon fonctionnement du module, laisser tel quel.
        'get_recipients_methods' => [
            [
                'key'           => 'by_role',
                'label'         => 'Personnes par rôle',                                   // Intitulé
                'description'   => 'Selectionne les personnes en fonction de leurs rôles', // Description
                'getRecipients' => [],
            ],
            [
                'key'           => 'by_intervenant',
                'label'         => 'Personnes par rôle',                                   // Intitulé
                'description'   => 'Selectionne les personnes en fonction de leurs rôles', // Description
                'getRecipients' => [],
            ],
        ],

    ],
```

# Activer la signature électronique dans OSE








