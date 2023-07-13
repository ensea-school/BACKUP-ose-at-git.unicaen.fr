<template>
    <div :class="{'bg-success':contrat.VALIDATION_ID,'bg-default':!contrat.VALIDATION_ID}" class="card">
        <div class="card-header card-header-h3">
            <h5 v-if="contrat.LIBELLE_MISSION">
                Prime de fin de contrat N°{{ contrat.NUMERO }} - {{ contrat.LIBELLE_STRUCTURE }}
                <span class="float-end">Du <u-date :value="contrat.DATE_DEBUT_CONTRAT"/> au <u-date
                    :value="contrat.DATE_FIN_CONTRAT"/></span>
            </h5>

            <h5 v-if="!contrat.LIBELLE_MISSION">
                Prime de fin de contrat N°{{ contrat.NUMERO }} - {{ contrat.LIBELLE_STRUCTURE }}
                <span class="float-end">Du <u-date :value="contrat.DATE_DEBUT_CONTRAT"/> au <u-date
                    :value="contrat.DATE_FIN_CONTRAT"/></span>
            </h5>
            <h6 v-if="contrat.LIBELLE_MISSION">
                {{ contrat.LIBELLE_MISSION }} ({{ contrat.TYPE_MISSION }})
            </h6>
            <h6 v-else>
                {{ contrat.TYPE_MISSION }}
            </h6>

        </div>
        <form :action="declarationUrl" enctype="multipart/form-data" method="post">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <input :checked="contrat.FICHIER_ID" :disabled="contrat.VALIDATION_ID" name="prime" type="checkbox"
                               @change="enableForm"/>
                        En cochant cette case, je déclare sur l'honneur ne pas avoir d'autre contrat à suivre dans la fonction publique, et me rend éligible à
                        une
                        prime de fin de contrat.
                        <br/><br/>
                        <div>
                            <div>
                                <label class=" form-label">Suivi de la déclaration : </label>
                            </div>
                            <!--Etat du dépôt de la déclaration-->
                            <div v-if="contrat.FICHIER_ID">
                                <u-icon name="thumbs-up"
                                        variant="success"/>
                                Déclaration déposée le 26/05/1982 à 12:00 par Antony Le Courtes
                            </div>
                            <div v-else>
                                <u-icon name="thumbs-down"
                                        variant="info"/>
                                Aucune déclaration déposée

                            </div>
                            <!--Etat de la validation de la déclaration-->
                            <div v-if="contrat.VALIDATION_ID">
                                <u-icon name="thumbs-up"
                                        variant="success"/>
                                Déclaration validée le 26/05/1982 à 12:00 par Antony Le Courtes
                            </div>
                            <div v-else>
                                <u-icon name="thumbs-down"
                                        variant="info"/>
                                Aucune déclaration validée

                            </div>
                            <!--Eligibilité à la prime de fin de contrat-->
                            <div v-if="contrat.VALIDATION_ID">
                                <u-icon name="euro-sign"
                                        variant="success"/>
                                Intervenant éligible à la prime de fin de contrat
                            </div>
                            <div v-else>
                                <u-icon name="euro-sign"
                                        variant="info"/>
                                Intervenant non éligible à la prime de fin de contrat

                            </div>

                        </div>
                    </div>
                    <div v-if="!contrat.FICHIER_ID" class="col-md-6">

                        <div class="card text-dark bg-light">
                            <div class="card-header">
                                Dépôt de votre déclaration sur l'honneur
                            </div>
                            <div class="card-body">
                                <p class="card-text">En cochant <b>la case ci-contre</b> vous pourrez déposer votre déclaration sur l'honneur signée
                                    ci-dessous.
                                    Vous trouverez un exemple de déclaration en <a
                                        href=""> cliquant-ici</a></p>
                                <input ref="file" :disabled="disabledForm" name="files[]" type="file"/>

                            </div>
                            <div class="card-footer d-grid gap-2">

                                <input :disabled="disabledForm" class="btn btn-primary " type="submit" value="Envoyer">
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
                                <a :href="telechargerUrl">{{ contrat.FICHIER_NOM }}</a>


                            </div>
                            <div class="card-footer" style="text-align:right;">
                                <a v-if="contrat.FICHIER_ID && !contrat.VALIDATION_ID"
                                   :href="supprimerUrl"
                                   class="btn btn-danger"
                                   title="Supprimer"
                                   @click.prevent="supprimer">
                                    Supprimer
                                </a>&nbsp;
                                <a v-if="contrat.FICHIER_ID && !contrat.VALIDATION_ID"
                                   :href="validerUrl"
                                   class="btn btn-success"
                                   title="Valider"
                                   @click.prevent="valider">
                                    Valider
                                </a>&nbsp;
                                <a v-if="contrat.FICHIER_ID && contrat.VALIDATION_ID"
                                   :href="devaliderUrl"
                                   class="btn btn-danger d-grid gap-2"
                                   title="Dévalider"
                                   @click.prevent="devalider">
                                    Dévalider
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
            }),
            supprimerUrl: unicaenVue.url("intervenant/:intervenant/supprimer-declaration-prime/:contrat", {
                intervenant: this.intervenant,
                contrat: this.contrat.CONTRAT_ID,
            }),
            validerUrl: unicaenVue.url("intervenant/:intervenant/valider-declaration-prime/:contrat", {
                intervenant: this.intervenant,
                contrat: this.contrat.CONTRAT_ID,
            }),
            devaliderUrl: unicaenVue.url("intervenant/:intervenant/devalider-declaration-prime/:contrat", {
                intervenant: this.intervenant,
                contrat: this.contrat.CONTRAT_ID,
            }),
            telechargerUrl: unicaenVue.url("intervenant/:intervenant/telecharger-declaration-prime/:contrat", {
                intervenant: this.intervenant,
                contrat: this.contrat.CONTRAT_ID,
            }),
            disabledForm: true,

        };
    },
    methods: {

        supprimer(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('reload');
            });
        },
        valider(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('reload');
            });
        },
        devalider(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('reload');
            });
        },
        enableForm(event)
        {
            this.disabledForm = !event.target.checked;
        }
    }


}

</script>

<style scoped>

</style>