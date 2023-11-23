<template>


    <div class=" card text-dark bg-light">
        <div class="card-header text-uppercase fw-bold">
            Demande de mise en paiement par lot
        </div>

        <div class="card-body">
            <form id="formDemandeMiseEnPaiement" action="" method="post">
                <div class="mb-3">
                    <label class="form-label" for="modele">Choisissez une structure </label>&nbsp;
                    <select v-model="selectedStructure" class="form-select" name="structure">
                        <option v-for="structure in structures" :value="structure.id">
                            {{ structure.libelle }}
                        </option>
                    </select>

                </div>
                <div class="mb-3">
                    <button id="btn-dmep-inprogress" class="btn btn-primary d-none" disabled type="button">
                        <span id="spinner" aria-hidden="true" class="spinner-border spinner-border-sm" role="status"></span>
                        &nbsp;Veuillez patienter...
                    </button>
                    <button id="btn-dmep" class="btn btn-primary" type="button" @click="findDemandeMiseEnPaiement">
                        Afficher les demandes de mise en paiement
                    </button>

                </div>
            </form>

        </div>
    </div>
    <div>
        <table class="table">
            <thead>
                <th>Intervenant</th>
                <th>Nombre d'heures</th>
            </thead>
            <tbody>

                <tr v-for="intervenant in this.intervenants">
                    <td>{{ intervenant.datasIntervenant.prenom + ' ' + intervenant.datasIntervenant.nom_usuel}}</td>
                    <td>25 heures</td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<script>


import UnicaenVue from "unicaen-vue/js/Client/unicaenVue";

export default {
    name: "DemandeMiseEnPaiementLot.vue",
    props: {
        structures: {required: true},
    },
    data()
    {
        return {
            selectedStructure: null,
            urlDemandeMiseEnPaiement: unicaenVue.url('paiement/demande-mise-en-paiement-lot'),
            intervenants: null,
        }
    },
    mounted()
    {

    },
    methods: {
        findDemandeMiseEnPaiement(event)
        {
            let form = document.getElementById('formDemandeMiseEnPaiement');
            let formData = new FormData(form);
            console.log(formData)
            //On desactive le bouton de soumission
            let btnDmep = document.getElementById('btn-dmep')
            let btnDmepInProgress = document.getElementById('btn-dmep-inprogress')
            btnDmepInProgress.classList.remove('d-none');
            btnDmep.classList.add('d-none');
            btnDmep.disabled = true;


            unicaenVue.axios.post(this.urlDemandeMiseEnPaiement, formData, {})
                .then(response => {
                    console.log('Formulaire soumis');
                    console.log(response);
                    this.intervenants = response.data;
                    btnDmep.disabled = false;
                    btnDmepInProgress.classList.add('d-none');
                    btnDmep.classList.remove('d-none');

                })
                .catch(error => {
                    console.error('Error dmep');
                })
        }
    }
}
</script>