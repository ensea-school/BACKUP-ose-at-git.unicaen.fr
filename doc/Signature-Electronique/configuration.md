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
        
       'log' => true,

        /*Cette partie permet de surcharger les personnes à qui seront envoyées les signatures électroniques. 
        L'envoi des emails étant géré directement par le parafeur cela permet de jouer des circuits de signature 
        en pré-production sans que les emails partent aux personnes concernées*/
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
                    //Les différents niveaux de signature à activer au sein de OSE, vous pouvez retirer les lignes que vous ne souhaitez pas utiliser avec ose
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
       

    ],
```

# Activer la signature électronique dans OSE

Pour activer la signature dans OSE, il faut se rendre dans les paramètres généraux de ose, et activer la signature électronique en choisissant le parapheur voulu.

![Activation parapheur électronique](param_generaux_signature.png)

Il vous faudra ensuite définit un circuit de signature dans administration > signature électronique > Gestion des circuits de signatures :

![circuit Signature électronique](circuit_signature.png)

![création circuit Signature électronique](gestion_circuit_signature.png)

Un fois le circuit de signature paramétré, il faut paramètrer l'état de sortie du contrat pour qu'il utilise ce circuit de signature en remplacement du fonctionnement habituel de OSE : 

![Paramètrage de l'état de sortie](parametrage_etat_sortie.png)

Maintenant les statuts ayant ce modèle d'état de sortie comme contrat pourront bénéficier de la signature électronique du contrat : 

![Signature électronique du contrat](contrat_signature.png)











