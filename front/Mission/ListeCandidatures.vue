<template>
    <div>
        <div v-if="this.renseignerDonneesPersonnelles" class="alert alert-primary" role="alert">
            Afin que vos candidatures soient étudiées, veuillez compléter <a
            :href="this.urlDonneesPersonnelles">vos
            données personnelles</a> et fournir les pièces justificatives qui vous seront demandées.
        </div>
        <table class="table table-bordered ">
            <thead>
            <tr>
                <th>Offre d'emploi</th>
                <th>Composante</th>
                <th>Etat</th>
                <th>Date commission</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="candidatures.length == 0">
                <td  colspan="5" style="text-align:center;">Aucune candidature</td>

            </tr>

            <tr v-for="candidature in candidatures" :key="candidature.id">
                <td style="text-align:center;"><a :href="urlOffre(candidature)">{{ candidature.offre.titre }}</a></td>
                <td style="text-align:center;">{{ candidature.offre.structure.libelleCourt }}</td>
                <td style="text-align:center;">
                    <span v-if="candidature.validation" class="badge rounded-pill bg-success">Acceptée par {{
                            candidature.validation.histoCreateur.displayName
                        }}</span>
                    <span v-if="!candidature.validation && candidature.motif !== null"
                          class="badge rounded-pill bg-danger">{{ candidature.motif }}</span>
                    <span v-if="!candidature.validation && candidature.motif === null"
                          class="badge rounded-pill bg-warning">En attente d'acceptation</span>
                </td>
                <td>
                    <u-date v-if="candidature.dateCommission" :value="candidature.dateCommission"/>
                </td>

                <td style="text-align:center;">
                    <a v-if="!candidature.validation && candidature.canValider"
                       :href="urlAccepterCandidature(candidature)"
                       class="btn btn-success"
                       data-content="Êtes vous sûr de vouloir accepter cette candidature ?"
                       data-title="Accepter la candidature"
                       style="color:white;"
                       title="Accepter la candidature"
                       @click.prevent="validerCandidature">
                        <i class="fa-solid fa-check"></i>
                    </a>&nbsp;
                    <a v-if="!candidature.motif && candidature.canRefuser" :href="urlRefuserCandidature(candidature)"
                       class="btn btn-danger"
                       data-content="Êtes vous sûr de vouloir refuser cette candidature ?"
                       data-title="Refuser la candidature"
                       style="color:white;"
                       title="Refuser la candidature"
                       @click.prevent="refuserCandidature">
                        <i class="fa-sharp fa-solid fa-xmark"></i>
                    </a>
                </td>
            </tr>

            </tbody>

        </table>
        <a :href="urlListeOffre"
           class="btn btn-primary"
           title="Voir les offres d'emploi"
        >
            <u-icon name="eye"/>
            Voir toutes les offres d'emploi
        </a>&nbsp;
    </div>

</template>

<script>


export default {
    name: "ListeCandidatures.vue",
    props: {
        intervenant: {required: true},
        renseignerDonneesPersonnelles: {type: Boolean, required: false},

    },
    data()
    {

        return {
            candidatures: [],
            urlListeOffre: unicaenVue.url('offre-emploi'),
        }

    },
    mounted()
    {
        this.reload();

    },
    computed:
        {
            urlDonneesPersonnelles: function () {
                return unicaenVue.url("intervenant/:intervenant/dossier", {intervenant: this.intervenant});
            }
        },
    methods: {
        reload()
        {
            unicaenVue.axios.get(
                unicaenVue.url("intervenant/:intervenant/get-candidatures", {intervenant: this.intervenant})
            ).then(response => {
                this.candidatures = response.data;

            });

        },
        validerCandidature(event)
        {
            modAjax(event.currentTarget, (widget) => {
                this.reload();
            });
        },
        refuserCandidature(event)
        {
            popConfirm(event.target, (response) => {
                this.reload();
            });
        },
        urlOffre(candidature)
        {
            return unicaenVue.url('offre-emploi/detail/:offre', {offre: candidature.offre.id});
        },
        urlAccepterCandidature: function (candidature) {
            return unicaenVue.url('offre-emploi/accepter-candidature/:id', {id: candidature.id})
        },
        urlRefuserCandidature: function (candidature) {
            return unicaenVue.url('offre-emploi/refuser-candidature/:id', {id: candidature.id})
        }


    }

}

</script>

<style scoped>

</style>