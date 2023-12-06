<template>
    <h1>Demande de mise en paiement par lots</h1>


    <div class=" card text-dark bg-light">
        <div class="card-header text-uppercase fw-bold">
            Recherchez des heures restantes à payer :
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

    <div class="alert alert-info" role="alert">
        Seules les HETD avec des centres de coûts pré-paramètrés peuvent bénéficier d'une demande de mise en paiement automatisées. Pour les autres, il faudra
        passer sur chaque fiches intervenant pour faire les demandes en sélectionnant le centre de coût manuellement.
    </div>
    <div v-if="this.intervenants.length = 0 && this.selectedStructure" class="alert alert-light text-center" role="alert">
        Aucune heure en attente de mise en paiement pour cette structure
    </div>
    <!--BUDGET-->
    <div>
        <table class="table">
            <thead>
            <th></th>
            <th>Paie etat</th>
            <th>Ressource propre</th>
            </thead>
            <tbody>
            <tr>
                <td>Budget prévisionnel</td>
                <td> {{ this.dotation }} HETD</td>
                <td> {{ this.dotation }} HETD</td>
            </tr>
            <tr>
                <td>Budget réalisé</td>
                <td> {{ this.liquidation }} HETD</td>
                <td> {{ this.liquidation }} HETD</td>
            </tr>

            </tbody>
        </table>

    </div>
    <div id="dmep" class="accordion">
        <form id="formProcessDemandeMiseEnPaiement" action="" method="post">
            <!--Permanents-->
            <div v-if="this.permanents.length > 0" class="accordion-item">
                <h2 id="dmep-permanents-heading" class="accordion-header">
                    <button aria-controls="dmep-permanents-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-permanents-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        Permanent(s)
                    </button>
                </h2>
                <div id="dmep-permanents-collapse" aria-labelledby="dmep-permanents-heading" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <th scope="col"><input id="allPermanents" checked="checked" class="checkbox-permanent" name="allPermanents" type="checkbox"
                                                   @click="toggleCheckbox"></th>
                            <th scope="col">Intervenant</th>
                            <th scope="col">HETD avec centre coût</th>
                            <th scope="col">HETD sans centre coût</th>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.permanents">
                                <td><input :id="'permanent-' + intervenant.datasIntervenant.id"
                                           :data-ressource-etat="totalRessourcePaieEtat(intervenant.heures)"
                                           :data-ressource-propre="totalRessourcePropre(intervenant.heures)"
                                           :disabled="totalPayable(intervenant.heures) == 0 "
                                           :name="'intervenant[' + intervenant.datasIntervenant.id +']'" checked="checked" class="checkbox-permanent"
                                           type="checkbox"></td>
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
                        Vacataire(s)
                    </button>
                </h2>
                <div id="dmep-vacataires-collapse" aria-labelledby="dmep-vacataires-heading" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <th><input id="allVacataire" checked="checked" class="checkbox-vacataire" name="allVacataire" type="checkbox"
                                       @click="toggleCheckbox"></th>
                            <th>Intervenant</th>
                            <th>HETD avec centre coût</th>
                            <th>HETD sans centre coût</th>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.vacataires">
                                <td><input :id="'vacataire-' + intervenant.datasIntervenant.id"
                                           :data-ressource-etat="totalRessourcePaieEtat(intervenant.heures)"
                                           :data-ressource-propre="totalRessourcePropre(intervenant.heures)"
                                           :disabled="totalPayable(intervenant.heures) == 0 "
                                           :name="'intervenant[' + intervenant.datasIntervenant.id +']'"
                                           :title="totalPayable(intervenant.heures) == 0?'Aucune heure pré-paramétrée avec un centre de coût ne peut bénéficier d\'une demande de mise en paiement':''"
                                           checked="checked"
                                           class="checkbox-vacataire"
                                           type="checkbox">
                                </td>
                                <td><a :href="urlIntervenant(intervenant)"
                                       target="_blank">{{ intervenant.datasIntervenant.prenom + ' ' + intervenant.datasIntervenant.nom_usuel }}</a></td>
                                <td><span
                                    :title="totalRessourcePaieEtat(intervenant.heures) + ' HETD en paie état / ' + totalRessourcePropre(intervenant.heures) + ' HETD en ressource propre' ">{{
                                        totalPayable(intervenant.heures)
                                    }} h</span></td>
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
                        Etudiant(s)
                    </button>
                </h2>
                <div id="dmep-etudiants-collapse" aria-labelledby="dmep-etudiants-heading" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <th><input id="allEtudiants" checked="checked" class="checkbox-etudiant" name="allEtudiants" type="checkbox"
                                       @click="toggleCheckbox"></th>
                            <th>Intervenant</th>
                            <th>HETD avec centre coût</th>
                            <th>HETD sans centre coût</th>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.etudiants">
                                <td><input :id="'etudiant-' + intervenant.datasIntervenant.id"
                                           :data-ressource-etat="totalRessourcePaieEtat(intervenant.heures)"
                                           :data-ressource-propre="totalRessourcePropre(intervenant.heures)"
                                           :disabled="totalPayable(intervenant.heures) == 0 "
                                           :name="'intervenant[' + intervenant.datasIntervenant.id +']'"
                                           checked="checked" class="checkbox-etudiant" type="checkbox"></td>
                                <td>{{ intervenant.datasIntervenant.prenom + ' ' + intervenant.datasIntervenant.nom_usuel }}</td>
                                <td>{{ totalPayable(intervenant.heures) }} h</td>
                                <td>{{ totalNonPayable(intervenant.heures) }} h</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Autres-->
            <div v-if="this.autres.length > 0" class="accordion-item">
                <h2 id="dmep-autres-heading" class="accordion-header">
                    <button aria-controls="dmep-autres-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-autres-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        Autre(s)
                    </button>
                </h2>
                <div id="dmep-autres-collapse" aria-labelledby="dmep-autres-heading" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <th><input id="allAutres" checked="checked" class="checkbox-autre" name="allAutres" type="checkbox" @click="toggleCheckbox"></th>
                            <th>Intervenant</th>
                            <th>HETD avec centre coût</th>
                            <th>HETD sans centre coût</th>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.etudiants">
                                <td><input :id="'autre-' + intervenant.datasIntervenant.id"
                                           :data-ressource-etat="totalRessourcePaieEtat(intervenant.heures)"
                                           :data-ressource-propre="totalRessourcePropre(intervenant.heures)"
                                           :disabled="totalPayable(intervenant.heures) == 0 " :name="'intervenant[' + intervenant.datasIntervenant.id +']'"
                                           checked="checked"
                                           class="checkbox-autre"
                                           type="checkbox"></td>
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
        return {
            selectedStructure: null,
            urlRechercheDemandeMiseEnPaiement: unicaenVue.url('paiement/demande-mise-en-paiement-lot'),
            urlProcessDemandeMiseEnPaiement: unicaenVue.url('paiement/process-demande-mise-en-paiement-lot'),
            permanents: [],
            vacataires: [],
            etudiants: [],
            autres: [],
            intervenants: [],
            dotation: null,
            liquidation: null,
            previsionnel: null,
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

            //On récupére le budget de la structure
            console.log(this.selectedStructure);


            unicaenVue.axios.get(unicaenVue.url('budget/get-budget-structure/:structure', {structure: this.selectedStructure}))
                .then(response => {
                    let datas = response.data;
                    this.dotation = datas.dotation;
                    this.liquidation = datas.liquidation;
                })



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
            //On desactive le bouton de soumission
            let btnPdmep = document.getElementById('btn-pdmep')
            let btnPdmepInProgress = document.getElementById('btn-pdmep-inprogress')
            btnPdmepInProgress.classList.remove('d-none');
            btnPdmep.classList.add('d-none');
            btnPdmep.disabled = true;


            unicaenVue.axios.post(this.urlProcessDemandeMiseEnPaiement, formData, {})
                .then(response => {
                    this.findDemandeMiseEnPaiement()
                    btnPdmep.disabled = false;
                    btnPdmepInProgress.classList.add('d-none');
                    btnPdmep.classList.remove('d-none');


                })
                .catch(error => {
                    console.error('Error process dmep');
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
            return total.toLocaleString('fr-FR', {maximumFractionDigits: 2});

        },
        totalNonPayable(heures)
        {
            let total = 0;
            heures.forEach((item, index) => {
                if (item.centreCout.code == '') {
                    total += item.heuresAPayer;
                }
            })


            return total.toLocaleString('fr-FR', {maximumFractionDigits: 2});


        },
        totalRessourcePaieEtat(heures)
        {
            let total = 0;
            heures.forEach((item, index) => {
                if (item.centreCout.typeRessourceCode == 'paie-etat') {
                    total += item.heuresAPayer;
                }
            })

            return total.toLocaleString('fr-FR', {maximumFractionDigits: 2});

        },
        totalRessourcePropre(heures)
        {
            let total = 0;
            heures.forEach((item, index) => {
                if (item.centreCout.typeRessourceCode == 'ressources-propres') {
                    total += item.heuresAPayer;
                }
            })

            return total.toLocaleString('fr-FR', {maximumFractionDigits: 2});

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
                        this.intervenants.push(intervenant);
                        break;
                    case 'Intervenant permanent':
                        this.permanents.push(intervenant);
                        this.intervenants.push(intervenant);
                        break;
                    case 'Étudiant':
                        this.etudiants.push(intervenant);
                        this.intervenants.push(intervenant);
                        break;
                    default:
                        this.autres.push(intervenant);
                        this.intervenants.push(intervenant);
                }



            });


        },

        toggleCheckbox(event)
        {
            //on récupere toutes les checkbox que l'on doit traiter
            let checkbox = Array.from(document.getElementsByClassName(event.target.className));
            if (event.target.checked) {
                checkbox.forEach(function (element, index)
                {
                    element.checked = true;
                });
            } else {
                checkbox.forEach(function (element, index)
                {
                    element.checked = false;
                });
            }

        },

        urlIntervenant(intervenant)
        {
            return unicaenVue.url('intervenant/code::intervenantCode/mise-en-paiement/demande', {
                intervenantCode: intervenant.datasIntervenant.code,
            })

        }

    }
}
</script>