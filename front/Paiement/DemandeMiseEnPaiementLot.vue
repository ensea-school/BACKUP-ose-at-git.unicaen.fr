<template>


    <div class=" card text-dark bg-light">
        <div class="card-header text-uppercase fw-bold">
            Demande de mise en paiement par lot
        </div>

        <div class="card-body">
            <form id="formRechercheDemandeMiseEnPaiement" action="" method="post">
                <div class="mb-3">
                    <label class="form-label" for="modele">Choisissez une structure </label>&nbsp;
                    <select v-model="selectedStructure" class="form-select" name="structure">
                        <option v-for="structure in structures" :value="structure.id">
                            {{ structure.libelle }}
                        </option>
                    </select>

                </div>
                <div class="mb-3">
                    <button id="btn-rdmep-inprogress" class="btn btn-primary d-none" disabled type="button">
                        <span id="spinner" aria-hidden="true" class="spinner-border spinner-border-sm" role="status"></span>
                        &nbsp;Veuillez patienter...
                    </button>
                    <button id="btn-rdmep" class="btn btn-primary" type="button" @click="findDemandeMiseEnPaiement">
                        Rechercher les heures pouvants bénéficier d'une demande de mise en paiement
                    </button>

                </div>
            </form>

        </div>
    </div>


    <div id="dmep" class="accordion">
        <form id="formProcessDemandeMiseEnPaiement" action="" method="post">
            <!--Permanents-->
            <div v-if="this.permanents.length > 0" class="accordion-item">
                <h2 id="dmep-permanents-heading" class="accordion-header">
                    <button aria-controls="dmep-permanents-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-permanents-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        Vacataire
                    </button>
                </h2>
                <div id="dmep-permanents-collapse" aria-labelledby="dmep-permanents-heading" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <th scope="col"><input id="allPermanents" checked="checked" name="allPermanents" type="checkbox"></th>
                            <th scope="col">Intervenant</th>
                            <th scope="col">HETD avec centre coût</th>
                            <th scope="col">HETD sans centre coût</th>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.permanents">
                                <td><input id="" :name="'intervenant[' + intervenant.datasIntervenant.id +']'" checked="checked" type="checkbox"></td>
                                <td>{{ intervenant.datasIntervenant.prenom + ' ' + intervenant.datasIntervenant.nom_usuel }}</td>
                                <td>{{ totalPayable(intervenant.heures) }} h</td>
                                <td>{{ totalNonPayable(intervenant.heures) }} h</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Vacataires-->
            <div v-if="this.vacataires.length > 0" class="accordion-item">
                <h2 id="dmep-vacataires-heading" class="accordion-header">
                    <button aria-controls="dmep-vacataires-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-vacataires-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        Vacataire
                    </button>
                </h2>
                <div id="dmep-vacataires-collapse" aria-labelledby="dmep-vacataires-heading" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <th><input id="allVacataire" checked="checked" name="allVacataire" type="checkbox"></th>
                            <th>Intervenant</th>
                            <th>HETD avec centre coût</th>
                            <th>HETD sans centre coût</th>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.vacataires">
                                <td><input id="" :name="'intervenant[' + intervenant.datasIntervenant.id +']'" checked="checked" type="checkbox"></td>
                                <td>{{ intervenant.datasIntervenant.prenom + ' ' + intervenant.datasIntervenant.nom_usuel }}</td>
                                <td>{{ totalPayable(intervenant.heures) }} h</td>
                                <td>{{ totalNonPayable(intervenant.heures) }} h</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Etudiant-->
            <div v-if="this.etudiants.length > 0" class="accordion-item">
                <h2 id="dmep-etudiants-heading" class="accordion-header">
                    <button aria-controls="dmep-etudiants-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-etudiants-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        Vacataire
                    </button>
                </h2>
                <div id="dmep-etudiants-collapse" aria-labelledby="dmep-etudiants-heading" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <th><input id="allEtudiants" checked="checked" name="allEtudiants" type="checkbox"></th>
                            <th>Intervenant</th>
                            <th>HETD avec centre coût</th>
                            <th>HETD sans centre coût</th>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.etudiants">
                                <td><input id="" :name="'intervenant[' + intervenant.datasIntervenant.id +']'" checked="checked" type="checkbox"></td>
                                <td>{{ intervenant.datasIntervenant.prenom + ' ' + intervenant.datasIntervenant.nom_usuel }}</td>
                                <td>{{ totalPayable(intervenant.heures) }} h</td>
                                <td>{{ totalNonPayable(intervenant.heures) }} h</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Etudiant-->
            <div v-if="this.autres.length > 0" class="accordion-item">
                <h2 id="dmep-autres-heading" class="accordion-header">
                    <button aria-controls="dmep-autres-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-autres-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        Vacataire
                    </button>
                </h2>
                <div id="dmep-autres-collapse" aria-labelledby="dmep-autres-heading" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <th><input id="allAutres" checked="checked" name="allAutres" type="checkbox"></th>
                            <th>Intervenant</th>
                            <th>HETD avec centre coût</th>
                            <th>HETD sans centre coût</th>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.etudiants">
                                <td><input id="" :name="'intervenant[' + intervenant.datasIntervenant.id +']'" checked="checked" type="checkbox"></td>
                                <td>{{ intervenant.datasIntervenant.prenom + ' ' + intervenant.datasIntervenant.nom_usuel }}</td>
                                <td>{{ totalPayable(intervenant.heures) }} h</td>
                                <td>{{ totalNonPayable(intervenant.heures) }} h</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button id="btn-pdmep-inprogress" class="btn btn-primary d-none" disabled type="button">
                    <span id="spinner" aria-hidden="true" class="spinner-border spinner-border-sm" role="status"></span>
                    &nbsp;Veuillez patienter...
                </button>
                <button id="btn-pdmep" class="btn btn-primary" type="button" @click="processDemandeMiseEnPaiement">
                    Enregistrer les demandes de paiement
                </button>

            </div>
        </form>
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
        console.log(this.structures);
        return {
            selectedStructure: null,
            urlRechercheDemandeMiseEnPaiement: unicaenVue.url('paiement/demande-mise-en-paiement-lot'),
            urlProcessDemandeMiseEnPaiement: unicaenVue.url('paiement/process-demande-mise-en-paiement-lot'),
            permanents: [],
            vacataires: [],
            etudiants: [],
            autres: [],
        }
    },
    mounted()
    {

    },
    methods: {
        findDemandeMiseEnPaiement(event)
        {
            let form = document.getElementById('formRechercheDemandeMiseEnPaiement');
            let formData = new FormData(form);
            //On desactive le bouton de soumission
            let btnRdmep = document.getElementById('btn-rdmep')
            let btnRdmepInProgress = document.getElementById('btn-rdmep-inprogress')
            btnRdmepInProgress.classList.remove('d-none');
            btnRdmep.classList.add('d-none');
            btnRdmep.disabled = true;


            unicaenVue.axios.post(this.urlRechercheDemandeMiseEnPaiement, formData, {})
                .then(response => {

                    this.dispatchDatas(response.data)
                    btnRdmep.disabled = false;
                    btnRdmepInProgress.classList.add('d-none');
                    btnRdmep.classList.remove('d-none');

                })
                .catch(error => {
                    console.error(error);
                })
        },
        processDemandeMiseEnPaiement(event)
        {
            let form = document.getElementById('formProcessDemandeMiseEnPaiement');
            let formData = new FormData(form);
            console.log(formData);
            //On desactive le bouton de soumission
            let btnPdmep = document.getElementById('btn-pdmep')
            let btnPdmepInProgress = document.getElementById('btn-pdmep-inprogress')
            btnPdmepInProgress.classList.remove('d-none');
            btnPdmep.classList.add('d-none');
            btnPdmep.disabled = true;


            unicaenVue.axios.post(this.urlProcessDemandeMiseEnPaiement, formData, {})
                .then(response => {



                })
                .catch(error => {
                    console.error('Error dmep');
                })
        },
        totalPayable(heures)
        {
            let total = 0;
            heures.forEach((item, index) => {
                if (item.centreCout.code != '') {
                    total += item.heuresAPayer;
                }
            })
            if (total % 1 !== 0) {
                return total.toFixed(2);
            }

            return total;
        },
        totalNonPayable(heures)
        {
            let total = 0;
            heures.forEach((item, index) => {
                if (item.centreCout.code == '') {
                    total += item.heuresAPayer;
                }
            })

            if (total % 1 !== 0) {
                return total.toFixed(2);
            }

            return total;

        },
        dispatchDatas(datas)
        {
            this.vacataires = [];
            this.permanents = [];
            this.etudiants = [];
            this.autres = [];

            datas.forEach((intervenant, index) => {
                switch (intervenant.datasIntervenant.typeIntervenant) {

                    case 'Vacataire':
                        this.vacataires.push(intervenant);
                        this.permanents.push(intervenant);
                        break;
                    case 'Permanent':
                        this.permanents.push(intervenant);
                        break;
                    case 'Étudiant':
                        this.etudiants.push(intervenant);
                        break;
                    default:
                        this.autres.push(intervenant);
                }



            });
            console.log(this.vacataires);
            console.log(this.permanents);
            console.log(this.etudiants);

        }

    }
}
</script>