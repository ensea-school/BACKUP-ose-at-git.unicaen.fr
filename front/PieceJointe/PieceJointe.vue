<template>
    <div class="tpj tpj-obligatoire tpj-1 card bg-default upload-container">
        <div class="card-header card-header-h3">
            <h5>
                <div class="validation-bar float-end" data-url="">

                    <!-- actions de validation de la pièce jointe entière -->
                    <a class="valider-pj btn btn-success" href=""
                       :title="'Valider la pièce justificative \'' +  datas.typePieceJointe.libelle + '\''">
                        <i class="fas fa-thumbs-up"></i> Valider
                    </a>

                    <!--    data-loading-text="Patientez..."-->

                    <a class="refuser-pj btn btn-danger ajax-modal"
                       href=""
                       title="Refuser la pièce justificative  '{{ datas.typePieceJointe.libelle}}'"
                    >
                        <i class="fas fa-trash-can"></i> Refuser
                    </a>
                </div>
                {{ datas.typePieceJointe.libelle }}
                <br>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Fichiers déposés :</label>
                    <div class="uploaded-files-div" id="uploaded-files-div-6851831280ff7"
                         data-url="/piece-jointe/intervenant/1011799/fichier/lister/1/105492" style="">
                        <ul>
                            <li class="fichier-pj">
                                <a class="download-file"
                                   href="/piece-jointe/intervenant/1011799/fichier/telecharger/153250/CV%20.pdf"
                                   title="Télécharger le fichier déposé 'CV .pdf'">
                                    <span class="icon icon-file"></span> CV .pdf (<abbr title="706703 octets">690,1
                                    ko</abbr>)</a>


                                <!-- date de dépôt éventuelle du fichier -->
                                <br><span class="upload-date">le 04/09/2024 à 15:08</span>


                                <!-- lien de suppression du fichier -->
                                <a id="a-68518314b2c5f" class="delete-file btn btn-sm btn-danger"
                                   href="/piece-jointe/intervenant/1011799/fichier/supprimer/105492/153250"
                                   title="Supprimer le fichier déposé 'CV .pdf'"><span
                                    class="icon iconly icon-supprimer"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <!--Form upload-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "PieceJointe.vue",
    props: {
        datas: {required: true},
        intervenant: {required: true},
    },
    data()
    {
        return {};
    },
    methods: {}
}
</script>

<style scoped>

</style>