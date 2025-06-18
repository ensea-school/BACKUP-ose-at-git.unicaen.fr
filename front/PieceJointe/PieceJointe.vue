<template>
    <div class="tpj tpj-obligatoire tpj-1 card bg-success  upload-container">
        <div class="card-header card-header-h3 ">
            <h5>
                <div class="validation-bar float-end" data-url="">
                    <div v-if="this.datas.pieceJointe">
                        <!-- actions de validation de la pièce jointe entière -->
                        <button v-if="!this.datas.pieceJointe.validation" :id="'valider-' + this.datas.pieceJointe.id"
                                class="btn btn-success"
                                type="button"
                                :title="'Valider la pièce justificative \'' +  this.datas.typePieceJointe.libelle + '\''"
                                @click="actionPieceJointe($event)"
                                :data-url="this.urlValiderPiecesJointes">

                            <u-icon id="action" name="thumbs-up"
                                    style="color:black;"/>
                            Valider
                            <!--                        <u-icon id="waiting" name="spin" rotate="right"
                                                            style="color:white;display:none;"/>-->
                        </button>
                        <button v-if="!this.datas.pieceJointe.validation" :id="'refuser-' + this.datas.pieceJointe.id"
                                class="btn btn-danger"
                                type="button"
                                :title="'Refuser la pièce justificative \'' +  this.datas.typePieceJointe.libelle + '\''"
                                @click="actionPieceJointe($event, true)" title="Refuser la pièce jointe"
                                :data-url="this.urlRefuserPiecesJointes">
                            <u-icon id="action" name="trash"
                                    style="color:black;"/>
                            Refuser
                            <!--                        <u-icon id="waiting" name="spin" rotate="right"
                                                            style="color:white;display:none;"/>-->
                        </button>
                        <button v-if="this.datas.pieceJointe.validation" :id="'devalider-' + this.datas.pieceJointe.id"
                                class="btn btn-danger"
                                type="button"
                                :title="'Dévalider la pièce justificative \'' +  this.datas.typePieceJointe.libelle + '\''"
                                @click="actionPieceJointe($event)"
                                :data-url="this.urlDevaliderPiecesJointes">
                            <u-icon id="action" name="thumbs-up"
                                    style="color:black;"/>
                            Dévalider
                            <!--                        <u-icon id="waiting" name="spin" rotate="right"
                                                            style="color:white;display:none;"/>-->
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
        return {
            urlValiderPiecesJointes: unicaenVue.url('piece-jointe/intervenant/:intervenant/valider/:pieceJointe', {
                intervenant: this.intervenant,
                pieceJointe: this.datas.pieceJointe ? this.datas.pieceJointe.id : 0
            }),
            urlDevaliderPiecesJointes: unicaenVue.url('piece-jointe/intervenant/:intervenant/devalider/:pieceJointe', {
                intervenant: this.intervenant,
                pieceJointe: this.datas.pieceJointe ? this.datas.pieceJointe.id : 0
            }),
            urlRefuserPiecesJointes: unicaenVue.url('piece-jointe/intervenant/:intervenant/refuser/:pieceJointe', {
                intervenant: this.intervenant,
                pieceJointe: this.datas.pieceJointe ? this.datas.pieceJointe.id : 0
            }),


        };
    },
    methods: {

        refuserPieceJointe(event)
        {

        },
        actionPieceJointe(event, ajax = false)
        {
            if (ajax) {
                modAjax(event.currentTarget, (widget) => {
                    this.$emit('refresh');
                });
            } else {
                var url = event.currentTarget.dataset.url;

                unicaenVue.axios.get(url).then(response => {
                    this.$emit('refresh');

                }).catch(error => {
                    this.$emit('refresh');
                })
            }

        }

    }
}
</script>

<style scoped>

</style>