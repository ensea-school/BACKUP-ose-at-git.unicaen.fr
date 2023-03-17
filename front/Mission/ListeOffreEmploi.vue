<template>
    <div v-for="offre in listeOffres">
        <offreEmploi :key="offre.id" :taux="offre"
                     :listeTaux="listeOffreEmploi"></offreEmploi>
    </div>
    <a v-if="canEditTaux" class="btn btn-primary" :href="ajoutUrl" @click.prevent="ajout">Ajout d'un nouveau taux</a>
</template>

<script>

import offreEmploi from './OffreEmploi.vue';

export default {
    components: {
        offreEmploi
    },
    props: {
        canEditTaux: {type: Boolean, required: true},
    },
    data()
    {
        return {
            listeOffres: [],
            ajoutUrl: Util.url('taux/saisir')
        };
    },
    mounted()
    {
        this.reload();
    },
    methods: {
        ajout(event)
        {
            modAjax(event.target, (widget) => {
                this.reload();
            });
        },
        supprimer()
        {
            this.reload();
        },

        refresh(taux)
        {

        },
        reload()
        {
            axios.get(
                Util.url("offre-emploi/liste")
            ).then(response => {
                this.listeOffres = response.data;
            });
        },
    }
}
</script>

<style scoped>

</style>