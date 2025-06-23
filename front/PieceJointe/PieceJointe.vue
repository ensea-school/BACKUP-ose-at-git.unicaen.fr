<template>
    <div :class="[
        'tpj',
        'card',
        'upload-container',
        datas.pieceJointe?.validation ? 'bg-success' : 'bg-default',
        datas.pieceJointe?.id ? 'tpj-' + datas.pieceJointe.id : ''
    ]">
        <div class="card-header card-header-h3 ">
            <h5>
                <div class="validation-bar float-end" data-url="">
                    <div v-if="datas.pieceJointe">
                        <!-- actions de validation de la pièce jointe entière -->
                        <button v-if="!datas.pieceJointe.validation" :id="'valider-' + datas.pieceJointe.id"
                                class="btn btn-success me-2"
                                type="button"
                                :title="'Valider la pièce justificative \'' +  datas.typePieceJointe.libelle + '\''"
                                @click="actionPieceJointe($event)"
                                :data-url="urlValiderPiecesJointes">

                            <u-icon id="action" name="thumbs-up"
                                    style="color:black;"/>
                            Valider
                        </button>
                        <button v-if="!datas.pieceJointe.validation" :id="'refuser-' + datas.pieceJointe.id"
                                class="btn btn-danger"
                                type="button"
                                :title="'Refuser la pièce justificative \'' +  datas.typePieceJointe.libelle + '\''"
                                @click="actionPieceJointe($event, true)" title="Refuser la pièce jointe"
                                :data-url="urlRefuserPiecesJointes">
                            <u-icon id="action" name="trash"
                                    style="color:black;"/>
                            Refuser
                        </button>
                        <button v-if="this.datas.pieceJointe.validation" :id="'devalider-' + datas.pieceJointe.id"
                                class="btn btn-danger"
                                type="button"
                                :title="'Dévalider la pièce justificative \'' +  datas.typePieceJointe.libelle + '\''"
                                @click="actionPieceJointe($event)"
                                :data-url="urlDevaliderPiecesJointes">
                            <u-icon id="action" name="thumbs-up"
                                    style="color:black;"/>
                            Dévalider
                        </button>
                    </div>

                </div>
                {{ datas.typePieceJointe.libelle }}
                <br>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- fichier déposé -->
                    <label>Fichiers déposés :</label>
                    <p v-if="!this.datas.pieceJointe">Aucun</p>
                    <div class="uploaded-files-div" id="uploaded-files-div-6851831280ff7">
                        <ul>
                            <li v-for="(fichier, key) in datas.pieceJointe?.fichier" class="fichier-pj">
                                <div class="d-flex">
                                    <div>
                                        <a class="download-file"
                                           :href="'/piece-jointe/intervenant/' + intervenant + '/fichier/telecharger/' + fichier.id + '/' + fichier.nom"
                                           :title="'Télécharger le fichier déposé \'' + fichier.nom + '\''">
                                            <span class="icon icon-file"></span> {{ truncate(fichier.nom, 15) }} (<abbr
                                            :title="fichier.poids">{{ fichier.poids }}</abbr>)</a>


                                        <!-- date de dépôt éventuelle du fichier -->
                                        <span class="d-block small upload-date"><i>Déposé le {{
                                                fichier.date
                                            }}</i></span>

                                        <span class="d-block small" v-if="fichier.validation"><i>Validé le {{
                                                fichier.validation?.date
                                            }}</i></span>
                                    </div>
                                    <div>
                                        <!-- lien de suppression du fichier -->
                                        <button v-if="!fichier.validation"
                                                :id="'supprimer-fichier-' + fichier.id"
                                                class="delete-file btn btn-sm btn-danger ms-2 p-1 py-0"
                                                type="button"
                                                :title="'Supprimer le fichier déposé \'' + fichier.nom + '\''"
                                                @click="actionPieceJointe($event)"
                                                :data-url="'/piece-jointe/intervenant/' + intervenant + '/fichier/supprimer/' + datas.pieceJointe.id + '/' + fichier.id">

                                            <u-icon id="action" name="trash"
                                                    style="color:black;"/>
                                        </button>
                                        <!-- lien de validation du fichier -->
                                        <button v-if="!fichier.validation"
                                                :id="'valider-fichier-' + fichier.id"
                                                class="validate-file btn btn-sm btn-success ms-2 p-1 py-0"
                                                type="button"
                                                :title="'Valider le fichier déposé \'' + fichier.nom + '\''"
                                                @click="actionPieceJointe($event)"
                                                :data-url="'/piece-jointe/intervenant/' + intervenant + '/fichier/valider/' + datas.pieceJointe.id + '/' + fichier.id">

                                            <u-icon id="action" name="thumbs-up"
                                                    style="color:black;"/>
                                        </button>
                                    </div>
                                </div>

                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <!-- Formulaire sur 4 colonnes -->
                        <div class="col-md-8">
                            <form :id="formId" action=""
                                  enctype="multipart/form-data" method="post">
                                <div>
                                    <label class="form-label" for="importFile">Choisissez le fichier à déposer :</label>
                                    <input class="form-control form-control-sm" name="importFile" type="file"
                                           @change="handleFileUpload" multiple>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button :id="buttonId"
                                    class="btn btn-primary btn-sm w-100" disabled type="button"
                                    @click="importFile($event)"
                                    @change="handleFileUpload"
                                    :data-url="urlDeposerFichier">

                                <u-icon id="action" name="upload"/>
                                Déposer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import {ref, computed} from 'vue'

const props = defineProps(['intervenant', 'datas']);

// Emits
const emit = defineEmits(['refresh'])

// Références réactives
const selectedFiles = ref(null)

// Computed URLs
const urlValiderPiecesJointes = computed(() =>
    unicaenVue.url('piece-jointe/intervenant/:intervenant/valider/:pieceJointe', {
        intervenant: props.intervenant,
        pieceJointe: props.datas.pieceJointe ? props.datas.pieceJointe.id : 0
    })
)

const urlDevaliderPiecesJointes = computed(() =>
    unicaenVue.url('piece-jointe/intervenant/:intervenant/devalider/:pieceJointe', {
        intervenant: props.intervenant,
        pieceJointe: props.datas.pieceJointe ? props.datas.pieceJointe.id : 0
    })
)

const urlRefuserPiecesJointes = computed(() =>
    unicaenVue.url('piece-jointe/intervenant/:intervenant/refuser/:pieceJointe', {
        intervenant: props.intervenant,
        pieceJointe: props.datas.pieceJointe ? props.datas.pieceJointe.id : 0
    })
)

const urlDeposerFichier = computed(() =>
    unicaenVue.url('piece-jointe/intervenant/:intervenant/fichier/televerser/:typePieceJointe', {
        intervenant: props.intervenant,
        typePieceJointe: props.datas.typePieceJointe ? props.datas.typePieceJointe.id : 0
    })
)

// Computed pour les IDs dynamiques
const formId = computed(() => {
    const id = props.datas.typePieceJointe?.id ?? 'unknown';
    return `form-import-${id}`;
});
const buttonId = computed(() => {
    const id = props.datas.typePieceJointe?.id ?? 'unknown';
    return `btn-import-${id}`;
});

// Méthodes
function handleFileUpload(event)
{
    console.log(buttonId.value);
    selectedFiles.value = event.target.files
    const btn = document.getElementById(buttonId.value)
    if (btn) btn.disabled = false
}

function importFile(event)
{
    event.preventDefault()

    const btnImport = document.getElementById(buttonId.value)
    const btnImportInProgress = document.getElementById('btn-import-inprogress')

    btnImport.disabled = true
    const form = document.getElementById(formId.value)
    const formData = new FormData(form)
    const url = event.currentTarget.dataset.url
    unicaenVue.axios.post(url, formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(response => {
        emit('refresh');
    }).catch(error => {
        console.error('Error uploading');
    })


}

function actionPieceJointe(event, ajax = false)
{
    if (ajax) {
        modAjax(event.currentTarget, () => {
            emit('refresh')
        })
    } else {
        const url = event.currentTarget.dataset.url
        unicaenVue.axios.get(url).then(() => emit('refresh')).catch(() => emit('refresh'))
    }
}

function truncate(str, maxLength)
{
    if (!str) return '';
    return str.length > maxLength ? str.slice(0, maxLength) + '…' : str;
}

</script>


<style scoped>

</style>