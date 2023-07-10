<template>
    <div :class="{'bg-success':contrat.VALIDATION_ID,'bg-default':!contrat.VALIDATION_ID}" class="card">
        <div class="card-header card-header-h3">
            <h5 v-if="contrat.LIBELLE_MISSION">
                Prime de fin de contrat N°{{ contrat.NUMERO }} - {{ contrat.LIBELLE_STRUCTURE }}
                <span class="float-end">Du <u-date :value="contrat.DATE_DEBUT_CONTRAT"/> au <u-date
                    :value="contrat.DATE_FIN_CONTRAT"/></span>
            </h5>
            <h6 v-if="contrat.LIBELLE_MISSION">
                {{ contrat.LIBELLE_MISSION }} ({{ contrat.TYPE_MISSION }})
            </h6>
            <h5 v-if="!contrat.LIBELLE_MISSION">
                {{ contrat.TYPE_MISSION }}
            </h5>
        </div>
        <form :action="declarationUrl" enctype="multipart/form-data" method="post">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <input name="prime" type="checkbox"/>
                        En cochant cette case, je déclare sur l'honneur ne pas avoir d'autre contrat à suivre dans la fonction publique, et me rend éligible à
                        une
                        prime de fin de contrat.
                        <br/><br/>
                        <div>
                            <div>
                                <label class=" form-label">Suivi de la déclaration : </label>
                            </div>
                            <div>
                                <u-icon :name="contrat.FICHIER_ID ? 'thumbs-up' : 'thumbs-down'"
                                        :variant="contrat.FICHIER_ID ? 'success' : 'info'"/>
                                Déclaration déposée le 26/05/1982 à 12:00 par Antony Le Courtes
                            </div>
                            <div>
                                <u-icon :name="contrat.VALIDATION_ID ? 'thumbs-up' : 'thumbs-down'"
                                        :variant="contrat.VALIDATION_ID ? 'success' : 'info'"/>
                                Déclaration validée le 26/05/1982 à 12:00 par Jean Dupont
                            </div>
                            <div v-if="!contrat.VALIDATION_ID">
                                <u-icon name="euro-sign" variant="success"/>
                                Intervenant éligible à une prime de fin de contrat
                            </div>

                        </div>
                    </div>
                    <div v-if="!contrat.FICHIER_ID" class="col-md-6">

                        <div class="card text-dark bg-light">
                            <div class="card-header">
                                Dépôt de votre déclaration sur l'honneur
                            </div>
                            <div class="card-body">
                                <p class="card-text">Déposez votre déclaration sur l'honneur signée ci-dessous. Vous trouverez un exemple de déclaration en <a
                                    href=""> cliquant-ici</a></p>
                                <input ref="file" name="files[]" type="file" @change="uploadFile">
                                <input type="submit" value="Envoyer"/>

                            </div>
                        </div>
                    </div>
                    <div v-if="contrat.FICHIER_ID" class="col-md-6">

                        <div class="card text-dark bg-light">
                            <div class="card-header">
                                Dépôt de votre déclaration sur l'honneur
                            </div>
                            <div class="card-body">
                                <p class="card-text">Vous pouvez télécharger votre déclaration sur l'honneur ci-dessous : </p>
                                {{ contrat.FICHIER_NOM }}


                            </div>
                            <div class="card-footer">
                                <a v-if="contrat.FICHIER_ID && !contrat.VALIDATION_ID"
                                   class="btn btn-danger"
                                   title="Supprimer"
                                   @click.prevent="supprimer">
                                    Supprimer
                                </a>&nbsp;
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>


</template>

<script>

export default {
    name: "Prime.vue",
    props: {
        contrat: {required: true},
        intervenant: {required: true}

    },
    data()
    {
        return {
            declarationUrl: unicaenVue.url("intervenant/:intervenant/declaration-prime/:contrat", {
                intervenant: this.intervenant,
                contrat: this.contrat.CONTRAT_ID
            })

        };
    },
    methods: {

        supprimer(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('reload', this.offre);
            });
        },
    }


}

</script>

<style scoped>

</style>