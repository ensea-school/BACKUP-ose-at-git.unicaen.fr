<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$nom    = $_GET['nom'] ?? $_POST['nom'] ?? '';
$prenom = $_GET['prenom'] ?? $_POST['prenom'] ?? "Laurent";

?>

<form action="/unicaen-code/test6" id="testf">

    <input type="text" id="nom" name="nom" value="<?= $nom ?>"/>
    <input type="text" id="prenom" name="prenom" value="<?= $prenom ?>"/>
    <button type="submit">Submit!</button>

</form>

<button onclick="test()">Click!</button>


<script>

    function test()
    {
        const nom = document.getElementById('nom').value;

        //window.location.assign('/unicaen-code/test6?nom=' + nom);

        //window.location.href = '/unicaen-code/test6?nom=' + nom;

        //Intranav2.load('/unicaen-code/test6?nom=' + nom);


        document.getElementById('testf').submit();
    }


    const Intranav2 = {
        event: null,
        url: null,
        data: null,
        from: null,

        run()
        {
            console.log('Intranav2 Prochaine URL :', this.url);
            console.log('From ' + this.from);
            console.log(this.data);

            if (this.from != 'history' && this.from != 'navigation') {
                // Si on ne navigue pas dans l'historique, on le met à jour
                history.pushState(this.data, null, this.url);
            }
            this.event = null;
            this.url = null;
            this.data = null;
            this.from = null;
        },


        load(url, data)
        {
            this.event = null;
            this.url = url;
            this.data = data;
            this.from = 'load';
            this.run();
        },

        install()
        {
            document.addEventListener("DOMContentLoaded", this.doInstall);
        },


        doInstall()
        {
            // Mise à jour des infos depuis les clics sur les ancres
            document.addEventListener('click', (e) => {
                const target = e.target.closest('a, button');
                if (target && target.tagName === 'A') {
                    Intranav2.event = e;
                    Intranav2.url = target.href;
                    Intranav2.data = null;
                    Intranav2.from = 'a';
                    //Intranav2.run();
                }
            }, true);

            // Mise à jour des infos depuis les submits
            document.addEventListener('submit', (e) => {
                const form = e.target;
                const formData = new FormData(form);

                Intranav2.event = e;
                Intranav2.url = form.action;
                Intranav2.data = Object.fromEntries(formData.entries());
                Intranav2.from = 'submit';
                //Intranav2.run();
            }, true);

            // On capte les événements pour la nouvelle API navigation (Chrome* uniquement)
            if (window.hasOwnProperty('navigation')) {
                navigation.addEventListener('navigate', (e) => {
                    console.log(Intranav2.from);
                    if (!Intranav2.from) {
                        Intranav2.event = e;
                        Intranav2.url = e.destination.url;
                        Intranav2.data = null;
                    }
                    Intranav2.from = 'navigation';
                    Intranav2.run();
                    e.preventDefault();
                });
            } else {
                /*window.addEventListener('beforeunload', function (e) {
                    if (Intranav2.from) {
                        Intranav2.run();

                        e.preventDefault();
                        e.returnValue = "";

                        return "";
                    }
                });*/
            }

            // On capte le click sur l'historique
            window.addEventListener("popstate", (e) => {
                Intranav2.event = e;
                Intranav2.url = e.target.location.href;
                Intranav2.data = null;
                Intranav2.from = 'history';
                Intranav2.run();
            });
        },

    };

    Intranav2.install();

</script>