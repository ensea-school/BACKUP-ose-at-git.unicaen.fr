<template>


    <div class=" card text-dark bg-light">
        <div class="card-header text-uppercase fw-bold">
            Importation des numéros de prise en charge
        </div>

        <div class="card-body">
            <div v-if="this.messageErrors" class="alert alert-danger" role="alert">
                {{ this.messageErrors }}
            </div>
            <div v-if="this.messageConfirm" class="alert alert-success" role="alert">
                {{ this.messageConfirm }}
            </div>
            <form id="formImport" action="" enctype="multipart/form-data" method="post">
                <p class="fs-9 text">
                    Vous pouvez utiliser le modèle directement extrait de winpaie ou télécharger le modèle d'import générique en <a
                    href="/modeles/import-numero-pec.xlsx">cliquant
                    ici.</a>
                </p>
                <div class="mb-3">
                    <label class="form-label" for="importFile">Choisissez le fichier à importer :</label>&nbsp;
                    <input class="form-control" name="importFile" type="file" @change="handleFileUpload">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="modele">Choisissez le modèle d'import :</label>&nbsp;
                    <select id="modeleImport" class="form-select" name="modeleImport">
                        <option value="winpaie">Winpaie</option>
                        <option value="generic">Generique (Modèle à 3 colonnes)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <button id="btn-import-inprogress" class="btn btn-primary d-none" disabled type="button">
                        <span id="spinner" aria-hidden="true" class="spinner-border spinner-border-sm" role="status"></span>
                        &nbsp;Veuillez patienter...
                    </button>
                    <button id="btn-import" class="btn btn-primary" disabled type="button" @click="importFile">
                        Importer les numéros de prise en charge
                    </button>
                    <!--                    <input id="btn-import" class="btn btn-primary" disabled type="submit" value="Importer les numéros de prise en charge">-->
                </div>
            </form>

        </div>


    </div>
    <div v-if="this.fileErrors || this.intervenantMissing" id="fileErrors" class="card text-dark bg-light">
        <div class="card-header text-uppercase fw-bold">
            Rapport de chargement du fichier
        </div>
        <div class="card-body">
            <div v-if="this.fileErrors.length != 0">
                <p>Listes des intervenants du fichier dont le numéro INSEE n'est pas valide : </p>
                <ul>
                    <li v-for="error in this.fileErrors">
                        {{}}
                    </li>
                </ul>
            </div>
            <div v-if="this.intervenantMissing.length != 0">
                <p>Listes des intervenants présents dans le fichier mais non trouvés dans OSE : </p>
                <ul>
                    <li v-for="intervenant in this.intervenantMissing">
                        {{ intervenant }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>


import UnicaenVue from "unicaen-vue/js/Client/unicaenVue";

export default {
    props: {
        canImportPec: {type: Boolean, required: false},
    },
    data()
    {
        return {
            selectedFile: null,
            importUrl: unicaenVue.url('paiement/import-numero-pec'),
            fileErrors: null,
            intervenantMissing: null,
            messageErrors: null,
            messageConfirm: null,
        }
    },
    mounted()
    {

    },
    methods: {
        handleFileUpload(event)
        {
            this.selectedFile = event.target.files[0];
            this.fileErrors = null;
            this.intervenantMissing = null
            this.messageErrors = null;
            this.messageConfirm = null;
            document.getElementById('btn-import').disabled = false;

        },
        importFile(event)
        {
            event.preventDefault();
            //On reintialiser le message d'erreur
            this.messageErrors = null;
            this.messageConfirm = null;

            //On desactive le bouton de soumission
            let btnImport = document.getElementById('btn-import')
            let btnImportInProgress = document.getElementById('btn-import-inprogress')
            btnImportInProgress.classList.remove('d-none');
            btnImport.classList.add('d-none');
            btnImport.disabled = true;

            let form = document.getElementById('formImport');
            let formData = new FormData(form);
            unicaenVue.axios.post(this.importUrl, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {

                    let datas = response.data;
                    this.fileErrors = datas.file;
                    this.intervenantMissing = datas.intervenant;
                    if (datas.message.length != 0) {
                        this.messageErrors = datas.message
                    } else {
                        this.messageConfirm = "Importation des numéros de prise en charge réalisée avec succés !";
                    }
                    btnImport.disabled = false;
                    btnImportInProgress.classList.add('d-none');
                    btnImport.classList.remove('d-none');

                })
                .catch(error => {
                    console.error('Error uploading');
                })
        }
    }
}
</script>