<template>
    <div class="row row-cols-1 row-cols-md-2 g-4">

        <div v-for="offre in offres">
            <offreEmploi @supprimer="supprimer" @refresh="refresh" :key="offre.id" :offre="offre"></offreEmploi>
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
            offres: [],
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
            let index = Util.json.indexById(this.offres, offre.id);
            this.offres[index] = offre;
        },

        reload()
        {
            axios.get(
                Util.url("offre-emploi/liste")
            ).then(response => {
                this.offres = response.data;
                console.log(this.offres);
            });
        },
    }
}
</script>

<style scoped>

</style>