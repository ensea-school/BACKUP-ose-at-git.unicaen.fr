<template>
    <div class="row row-cols-1 row-cols-md-2 g-4">

        <div v-for="offre in listeOffres">
            <offreEmploi @refresh="refresh" :key="offre.id" :offre="offre"></offreEmploi>
        </div>
    </div>
    <!-- <a v-if="canEditTaux" class="btn btn-primary" :href="ajoutUrl" @click.prevent="ajout">Ajout d'un nouveau taux</a>-->
</template>

<script>

import offreEmploi from './OffreEmploi.vue';

export default {
    components: {
        offreEmploi
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

        refresh(offre)
        {
            console.log(offre);
            let index = Util.json.indexById(this.listeOffres, offre.id);
            this.listeOffres[index] = offre;
        },

        reload()
        {
            axios.get(
                Util.url("offre-emploi/liste")
            ).then(response => {
                this.listeOffres = response.data;
                console.log(this.listeOffres);
            });
        },
    }
}
</script>

<style scoped>

</style>