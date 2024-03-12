<template>
    <div v-if="!this.datasServiceAPayer" class="text-center">
        <div class="mt-5 spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Chargement des demandes de mise en paiement en cours...</span>
        </div>
        Chargement des demandes de mise en paiement en cours...
    </div>
    <div id="accordionPanelsStayOpenExample" class="accordion">

        <demande-mise-en-paiement-structure v-for="(structure, code) in datasServiceAPayer"
                                            :datas="structure"
                                            :intervenant="intervenant"
                                            @refresh="findServiceAPayer"/>
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
            datasServiceAPayer: null,
            urlListeServiceAPayer: unicaenVue.url('intervenant/:intervenant/mise-en-paiement/liste-service-a-payer', {intervenant: this.intervenant}),
        }
    },

    methods: {
        findServiceAPayer()
        {

            unicaenVue.axios.get(this.urlListeServiceAPayer)
                .then(response => {
                    this.datasServiceAPayer = response.data;
                })
                .catch(error => {
                    console.error(error);
                })

        },

    },
    mounted()
    {
        this.findServiceAPayer();
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