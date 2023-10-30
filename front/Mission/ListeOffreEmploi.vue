<template>
    <!--On affiche une synthèse de la liste des offres-->
    <div v-if="!extended" class="row row-cols-1 row-cols-md-2 g-4 mb-3">
        <offreEmploi v-for="offre in offres" :key="offre.id" :canModifier="this.canModifier" :canSupprimer="this.canSupprimer" :canValider="this.canValider"
                     :canVoirCandidature="this.canVoirCandidature"
                     :offre="offre"
                     :decretText="offre.decretText"
                     @refresh="refresh"
                     @supprimer="supprimer"></offreEmploi>
    </div>
    <div v-if="!extended">
        <a v-if="this.canModifier" :href="ajoutUrl" class=" btn btn-primary" @click.prevent="ajout">Ajouter une nouvelle offre</a>
    </div>

    <!--On affiche une synthèse de la liste des offres-->
    <div v-if="extended">
        <offreEmploi v-for="offre in offres" :key="offre.id" :canModifier="this.canModifier" :canPostuler="this.canPostuler" :canSupprimer="this.canSupprimer"
                     :canValider="this.canValider"
                     :canValiderCandidature="this.canValiderCandidature"
                     :canVoirCandidature="this.canVoirCandidature"
                     :decretText="offre.decretText"
                     :extended="extended"
                     :offre="offre"
                     :utilisateur="this.utilisateur"
                     @refresh="refresh"
                     @supprimer="supprimer"></offreEmploi>
    </div>

</template>

<script>

import offreEmploi from './OffreEmploi.vue';

export default {
    components: {
        offreEmploi
    },
    props: {

        id: {type: Number, required: false},
        utilisateur: {required: false},
        canModifier: {type: Boolean, required: false},
        canPostuler: {type: Boolean, required: false},
        canValider: {type: Boolean, required: false},
        canVoirCandidature: {type: Boolean, required: false},
        canValiderCandidature: {type: Boolean, required: false},
        canSupprimer: {type: Boolean, required: false},



    },

    data()
    {

        return {
            offres: [],
            ajoutUrl: unicaenVue.url('offre-emploi/saisir'),
        };
    },
    mounted()
    {
        this.reload();

    },
    computed: {
        extended: function ()
        {
            if (this.id) {
                return true;
            }
            return false
        }
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
            window.location.href = unicaenVue.url("offre-emploi");

        },

        refresh(offre)
        {
            let index = Util.json.indexById(this.offres, offre.id);
            this.offres[index] = offre;
        },

        reload()
        {
            if (this.id) {
                unicaenVue.axios.get(
                    unicaenVue.url("offre-emploi/get/:offreEmploi", {offreEmploi: this.id})
                ).then(response => {
                    this.offres = [response.data];

                });

            } else {
                unicaenVue.axios.get(
                    unicaenVue.url("offre-emploi/liste")
                ).then(response => {
                    this.offres = response.data;

                });
            }


        }

    }
}
</script>

<style scoped>

</style>