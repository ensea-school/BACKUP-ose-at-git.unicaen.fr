<template>
    <div v-if="!this.datasDemandesMiseEnPaiement" class="text-center">
        <div class="mt-5 spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Chargement des demandes de mise en paiement en cours...</span>
        </div>
        Chargement des demandes de mise en paiement en cours...
    </div>
    <div id="accordionPanelsStayOpenExample" class="accordion">

        <demande-mise-en-paiement-structure v-for="(structure, code) in datasDemandesMiseEnPaiement"
                                            :datas="structure"
                                            :intervenant="intervenant"
                                            @refresh="getDemandesMiseEnPaiement"/>
    </div>
</template>

<script>

import DemandeMiseEnPaiementStructure from './DemandeMiseEnPaiementStructure.vue';


export default {

    name: "DemandeMiseEnPaiement",
    components: {DemandeMiseEnPaiementStructure},
    props: {
        intervenant: {required: false},
    },
    data()
    {
        return {
            datasDemandesMiseEnPaiement: null,
            urlGetDemandesMiseEnPaiement: unicaenVue.url('intervenant/:intervenant/mise-en-paiement/get-demandes-mise-en-paiement', {intervenant: this.intervenant}),
        }
    },

    methods: {
        getDemandesMiseEnPaiement()
        {

            unicaenVue.axios.get(this.urlGetDemandesMiseEnPaiement)
                .then(response => {
                    this.datasDemandesMiseEnPaiement = response.data;
                })
                .catch(error => {
                    console.error(error);
                })

        },

    },
    mounted()
    {
        this.getDemandesMiseEnPaiement();
    },
    updated()
    {
        $('.selectpicker').selectpicker('render');
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

    }



}

</script>