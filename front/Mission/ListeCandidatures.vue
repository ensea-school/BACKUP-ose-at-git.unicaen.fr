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
                <td style="text-align:center;">{{ candidature.offre.titre }}</td>
                <td style="text-align:center;">{{ candidature.offre.structure.libelleLong }}</td>
                <td style="text-align:center;">Computed à faire</td>
                <td style="text-align:center;">Action à définir</td>
            </tr>

            </tbody>

        </table>
    </div>

</template>

<script>

import offreEmploi from "@/Mission/OffreEmploi.vue";

export default {
    name: "ListeCandidatures.vue",
    components: {offreEmploi},
    props: {
        intervenant: {required: true},
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
        console.log(this.candidatures);

    },
    methods: {
        reload()
        {
            unicaenVue.axios.get(
                unicaenVue.url("intervenant/:intervenant/get-candidatures", {intervenant: this.intervenant})
            ).then(response => {
                this.candidatures = response.data;

            });

        }
    }

}

</script>

<style scoped>

</style>