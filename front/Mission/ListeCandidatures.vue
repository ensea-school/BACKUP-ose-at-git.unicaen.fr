<template>
    <div>
        <table class="table table-bordered ">
            <thead>
            <tr>
                <th>Offre d'emploi</th>
                <th>Composante</th>
                <th>Etat</th>
                <th v-if="canValiderCandidature">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="candidatures.length == 0">
                <td v-if="canValiderCandidature" colspan="4" style="text-align:center;">Aucune candidature</td>
                <td v-if="!canValiderCandidature" colspan="3" style="text-align:center;">Aucune candidature</td>
            </tr>
            <tr v-for="candidature in candidatures" :key="candidature.id">
                <td style="text-align:center;"><a :href="'/offre-emploi/detail/' + candidature.offre.id">{{ candidature.offre.titre }}</a></td>
                <td style="text-align:center;">{{ candidature.offre.structure.libelleLong }}</td>
                <td style="text-align:center;">
                    <span v-if="candidature.validation" class="badge rounded-pill bg-success">Acceptée par {{
                            candidature.validation.histoCreateur.displayName
                        }}</span>
                    <span v-if="!candidature.validation && candidature.motif !== null" class="badge rounded-pill bg-danger">{{ candidature.motif }}</span>
                    <span v-if="!candidature.validation && candidature.motif === null" class="badge rounded-pill bg-warning">En attente d'acceptation</span>
                </td>
                <td v-if="this.canValiderCandidature" style="text-align:center;">
                    <a :href="'/offre-emploi/accepter-candidature/' + candidature.id" v-if="!candidature.validation"
                       title="Accepter la candidature"
                       class="btn btn-success"
                       data-title="Accepter la candidature"
                       data-content="Etes vous sûre de vouloir accepter cette candidature ?"
                       @click.prevent="validerCandidature">Accepter </a>&nbsp;
                    <a :href="'/offre-emploi/refuser-candidature/' + candidature.id"
                       title="Refuser la candidature"
                       class="btn btn-danger"
                       data-title="Refuser la candidature"
                       data-content="Etes vous sûre de vouloir refuser cette candidature ?"
                       @click.prevent="refuserCandidature">Refuser </a>
                </td>
            </tr>

            </tbody>

        </table>
        <a href="/offre-emploi"
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
        canValiderCandidature: {type: Boolean, required: false},

    },
    data()
    {

        return {
            candidatures: []
        }

    },
    mounted()
    {
        this.reload();

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
            popConfirm(event.target, (response) => {
                this.reload();
            });
        },
        refuserCandidature(event)
        {
            popConfirm(event.target, (response) => {
                this.reload();
            });
        }
    }

}

</script>

<style scoped>

</style>