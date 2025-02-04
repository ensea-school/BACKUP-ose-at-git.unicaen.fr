<template>
    <div v-if="!this.datasDemandesMiseEnPaiement" class="text-center">
        <div class="mt-5 spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Chargement des demandes de mise en paiement en cours...</span>
        </div>
        Chargement des demandes de mise en paiement en cours...
    </div>

    <div v-if="this.datasDemandesMiseEnPaiement" id="accordionPanelsStayOpenExample" class="accordion">

        <demande-mise-en-paiement-structure @refresh-btn-state="btnResetState" v-for="(structure, code) in datasDemandesMiseEnPaiement"
                                            :datas="structure"
                                            :intervenant="intervenant"
                                            @refresh="getDemandesMiseEnPaiement"/>
    </div>
    <div v-if="!haveDemandeMiseEnPaiement && this.datasDemandesMiseEnPaiement"  class="text-center alert alert-secondary" role="alert">
        Les demandes de mises en paiement sont effectuées par la composante : {{ this.intervenantStructure}}
    </div>

</template>

<script>

import DemandeMiseEnPaiementStructure from './DemandeMiseEnPaiementStructure.vue';


export default {

    name: "DemandeMiseEnPaiement",
    components: {DemandeMiseEnPaiementStructure},
    props: {
        intervenant: {required: false},
        intervenantStructure : {required: false}
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
                }).then(response => {
                    this.btnResetState();
                }).catch(error => {
                    this.btnResetState();
                    console.error(error);
                })

        },
        btnResetState()
        {
            //Reset des boutons remove
            let elementsBtnRemove = Array.from(document.querySelectorAll('[id^="remove-"]'));
            elementsBtnRemove.forEach(el => {
                el.disabled = false;
                el.querySelector('#waiting').style.display = 'none';
                el.querySelector('#action').style.display = 'inline-block';
            });
            //Reset des boutons add
            let elementsBtnAdd = Array.from(document.querySelectorAll('[id^="add-"]'));
            elementsBtnAdd.forEach(el => {
                el.disabled = false;
                el.querySelector('#waiting').style.display = 'none';
                el.querySelector('#action').style.display = 'inline-block';
            });
            //Reset des boutons add-all
            let elementsBtnAddAll = Array.from(document.querySelectorAll('[id^="add-all"]'));
            elementsBtnAddAll.forEach(el => {
                el.disabled = false;
                el.querySelector('#waiting').style.display = 'none';
                el.querySelector('#action').style.display = 'inline-block';
            });


        },

    },
    computed: {
      haveDemandeMiseEnPaiement: function() {
          if(this.datasDemandesMiseEnPaiement)
          {
              return Object.keys(this.datasDemandesMiseEnPaiement).length > 0;
          }
          return false;
      }

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

    },




}

</script>