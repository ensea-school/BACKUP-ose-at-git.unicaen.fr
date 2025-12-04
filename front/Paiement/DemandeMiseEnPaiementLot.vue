<template>
    <h1>Demande de mise en paiement par lots</h1>


    <div class=" card text-dark bg-light">
        <div class="card-header text-uppercase fw-bold">
            Recherchez des heures en attente de demande de mise en paiement :
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
                        <span id="spinner" aria-hidden="true" class="spinner-border spinner-border-sm"
                              role="status"></span>
                        &nbsp;Veuillez patienter...
                    </button>
                    <button id="btn-rdmep" :disabled="(!selectedStructure)?true:false" class="btn btn-primary"
                            type="button" @click="findDemandeMiseEnPaiement">
                        Lancer la recherche
                    </button>

                </div>
            </form>

        </div>
    </div>

    <div class="alert alert-info" role="alert">
        Seules les HETD <strong>(hors référentiel)</strong> avec des centres de coûts pré-paramètrés peuvent bénéficier
        d'une demande de mise en paiement
        automatisées. Pour les
        autres, il faudra
        passer sur chaque fiches intervenant pour faire les demandes en sélectionnant le centre de coût manuellement.
    </div>

    <!--BUDGET-->
    <div v-if="haveDotation">
        <div v-if="alertDotation" class="alert alert-danger" role="alert">
            Attention vous dépassez vos dotations, vous ne pourrez pas lancer les demandes de mise en paiement par lot.
            Veuillez ajuster votre sélection
            d'intervenants pour faire les demandes de mise en paiement.
        </div>
        <table class="table table-bordered caption-top">

            <thead class="table-light">
            <tr>
                <th class="fw-bold" scope="col">Budget</th>
                <th class="fw-bold" scope="col">Paie etat</th>
                <th class="fw-bold" scope="col">Ressource propre</th>
                <th class="fw-bold" scope="col">Total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Dotation</td>
                <td> {{ formattedHETD(this.dotation.paieEtat) }} HETD</td>
                <td> {{ formattedHETD(this.dotation.ressourcePropre) }} HETD</td>
                <td class="fw-bold"> {{ formattedHETD(this.dotation.total) }} HETD</td>
            </tr>
            <tr>
                <td>Consommation</td>
                <td><span :class="alertPaieEtat"> {{ formattedHETD(this.totalConsommationPaieEtat) }} HETD</span></td>
                <td><span :class="alertRessourcePropre">{{
                        formattedHETD(this.totalConsommationRessourcePropre)
                    }} HETD</span></td>
                <td class="fw-bold"> {{ formattedHETD(this.totalConsommation) }} HETD</td>
            </tr>

            </tbody>
        </table>

    </div>
    <div id="dmep" class="accordion">
        <form id="formProcessDemandeMiseEnPaiement" action="" method="post">
            <!--Permanents-->
            <div v-if="this.permanents.length > 0" class="accordion-item">
                <h2 id="dmep-permanents-heading" class="accordion-header">
                    <button aria-controls="dmep-permanents-collapse" aria-expanded="true" class="accordion-button"
                            data-bs-target="#dmep-permanents-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        {{ this.permanents.length }} Permanent(s)
                    </button>
                </h2>
                <div id="dmep-permanents-collapse" aria-labelledby="dmep-permanents-heading"
                     class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"><input id="allPermanents" checked="checked" class="checkbox-permanent"
                                                       name="allPermanents" type="checkbox"
                                                       @click="toggleCheckbox"></th>
                                <th scope="col">Intervenant</th>
                                <th>HETD payables en lot</th>
                                <th>HETD sans centre de coût</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.permanents">
                                <td><input :id="'permanent-' + intervenant.datasIntervenant.id"
                                           :data-paie-etat="totalRessourcePaieEtat(intervenant.heures)"
                                           :data-ressource-propre="totalRessourcePropre(intervenant.heures)"
                                           :disabled="totalPayable(intervenant) == 0 || intervenant.datasIntervenant.incoherencePaiement"
                                           :name="'intervenant[' + intervenant.datasIntervenant.id +']'"
                                           :title="totalPayable(intervenant) == 0?'Aucune heure pré-paramétrée avec un centre de coût ne peut bénéficier d\'une demande de mise en paiement':''"
                                           checked="checked"
                                           class="checkbox-permanent"
                                           type="checkbox"
                                           @change="refreshTotalConsommation()"></td>
                                <td>
                                    <i title="La fiche intervenant contient des incohérences de paiement (trop payé, etc...), une revue manuelle des paiements est nécessaire."
                                       v-if="intervenant.datasIntervenant.incoherencePaiement"
                                       class="fas fa-triangle-exclamation" style="color:#a40000;cursor: help;"></i>&nbsp;

                                    <a :href="urlIntervenant(intervenant)"
                                       target="_blank">{{
                                        intervenant.datasIntervenant.nom_usuel.toUpperCase() + ' ' + intervenant.datasIntervenant.prenom
                                    }}</a></td>
                                <td><span
                                    :title="totalRessourcePaieEtat(intervenant.heures) + ' HETD en paie état / ' + totalRessourcePropre(intervenant.heures) + ' HETD en ressource propre' "
                                    style="text-decoration:underline dotted;cursor: help;">
                                    {{ totalPayable(intervenant) }} h</span></td>
                                <td><span style="text-decoration:underline dotted;cursor: help;"
                                          title="Manque un centre de coût et/ou un domaine fonctionnel">{{
                                        totalNonPayable(intervenant)
                                    }} h</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Vacataires-->
            <div v-if="this.vacataires.length > 0" class="accordion-item">
                <h2 id="dmep-vacataires-heading" class="accordion-header">
                    <button aria-controls="dmep-vacataires-collapse" aria-expanded="true" class="accordion-button"
                            data-bs-target="#dmep-vacataires-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        {{ this.vacataires.length }} Vacataire(s)
                    </button>
                </h2>
                <div id="dmep-vacataires-collapse" aria-labelledby="dmep-vacataires-heading"
                     class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th><input id="allVacataire" checked="checked" class="checkbox-vacataire"
                                           name="allVacataire" type="checkbox"
                                           @click="toggleCheckbox"></th>
                                <th>Intervenant</th>
                                <th>HETD payables en lot</th>
                                <th>HETD sans centre de coût</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.vacataires">
                                <td>
                                    <input
                                        :id="'vacataire-' + intervenant.datasIntervenant.id"
                                           :data-paie-etat="totalRessourcePaieEtat(intervenant.heures)"
                                           :data-ressource-propre="totalRessourcePropre(intervenant.heures)"
                                        :disabled="totalPayable(intervenant) == 0 || intervenant.datasIntervenant.incoherencePaiement"
                                           :name="'intervenant[' + intervenant.datasIntervenant.id +']'"
                                        :title="totalPayable(intervenant) == 0?'Aucune heure pré-paramétrée avec un centre de coût ne peut bénéficier d\'une demande de mise en paiement':''"
                                           checked="checked"
                                           class="checkbox-vacataire"
                                           type="checkbox"
                                           @change="refreshTotalConsommation()">
                                </td>
                                <td>
                                    <i title="La fiche intervenant contient des incohérences de paiement (trop payé, etc...), une revue manuelle des paiements est nécessaire."
                                       v-if="intervenant.datasIntervenant.incoherencePaiement"
                                       class="fas fa-triangle-exclamation" style="color:#a40000;cursor: help;"></i>&nbsp;
                                    <a :href="urlIntervenant(intervenant)"
                                       target="_blank">{{
                                        intervenant.datasIntervenant.nom_usuel.toUpperCase() + ' ' + intervenant.datasIntervenant.prenom
                                    }}</a></td>
                                <td><span
                                    :title="totalRessourcePaieEtat(intervenant.heures) + ' HETD en paie état / ' + totalRessourcePropre(intervenant.heures) + ' HETD en ressource propre' "
                                    style="text-decoration:underline dotted;cursor: help;">{{
                                        totalPayable(intervenant)
                                    }} h</span></td>
                                <td><span style="text-decoration:underline dotted;cursor: help;"
                                          title="Manque un centre de coût et/ou un domaine fonctionnel">{{
                                        totalNonPayable(intervenant)
                                    }} h</span></td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Etudiant-->
            <div v-if="this.etudiants.length > 0" class="accordion-item">
                <h2 id="dmep-etudiants-heading" class="accordion-header">
                    <button aria-controls="dmep-etudiants-collapse" aria-expanded="true" class="accordion-button"
                            data-bs-target="#dmep-etudiants-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        {{ this.etudiants.length }} Etudiant(s)
                    </button>
                </h2>
                <div id="dmep-etudiants-collapse" aria-labelledby="dmep-etudiants-heading"
                     class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th><input id="allEtudiants" checked="checked" class="checkbox-etudiant"
                                           name="allEtudiants" type="checkbox"
                                           @click="toggleCheckbox"></th>
                                <th>Intervenant</th>
                                <th>HETD payables en lot</th>
                                <th>HETD sans centre de coût</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.etudiants">
                                <td><input :id="'etudiant-' + intervenant.datasIntervenant.id"
                                           :data-paie-etat="totalRessourcePaieEtat(intervenant.heures)"
                                           :data-ressource-propre="totalRessourcePropre(intervenant.heures)"
                                           :disabled="totalPayable(intervenant) == 0 "
                                           :name="'intervenant[' + intervenant.datasIntervenant.id +']'"
                                           :title="totalPayable(intervenant) == 0?'Aucune heure pré-paramétrée avec un centre de coût ne peut bénéficier d\'une demande de mise en paiement':''"
                                           checked="checked" class="checkbox-etudiant" type="checkbox"
                                           @change="refreshTotalConsommation()"></td>
                                <td>
                                    <i title="La fiche intervenant contient des incohérences de paiement (trop payé, etc...), une revue manuelle des paiements est nécessaire."
                                       v-if="intervenant.datasIntervenant.incoherencePaiement"
                                       class="fas fa-triangle-exclamation" style="color:#a40000;cursor: help;"></i>&nbsp;

                                    <a :href="urlIntervenant(intervenant)"
                                       target="_blank">{{
                                        intervenant.datasIntervenant.nom_usuel.toUpperCase() + ' ' + intervenant.datasIntervenant.prenom
                                    }}</a></td>
                                <td><span
                                    :title="totalRessourcePaieEtat(intervenant.heures) + ' HETD en paie état / ' + totalRessourcePropre(intervenant.heures) + ' HETD en ressource propre' "
                                    style="text-decoration:underline dotted;cursor: help;">
                                    {{ totalPayable(intervenant) }} h</span></td>
                                <td><span style="text-decoration:underline dotted;cursor: help;"
                                          title="Manque un centre de coût et/ou un domaine fonctionnel">{{
                                        totalNonPayable(intervenant)
                                    }} h</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--Autres-->
            <div v-if="this.autres.length > 0" class="accordion-item">
                <h2 id="dmep-autres-heading" class="accordion-header">
                    <button aria-controls="dmep-autres-collapse" aria-expanded="true" class="accordion-button"
                            data-bs-target="#dmep-autres-collapse"
                            data-bs-toggle="collapse"
                            type="button">
                        {{ this.autres.length }} Autre(s)
                    </button>
                </h2>
                <div id="dmep-autres-collapse" aria-labelledby="dmep-autres-heading"
                     class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th><input id="allAutres" checked="checked" class="checkbox-autre" name="allAutres"
                                           type="checkbox" @click="toggleCheckbox">
                                </th>
                                <th>Intervenant</th>
                                <th>HETD payables en lot</th>
                                <th>HETD sans centre de coût</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="intervenant in this.etudiants">
                                <td><input :id="'autre-' + intervenant.datasIntervenant.id"
                                           :data-paie-etat="totalRessourcePaieEtat(intervenant.heures)"
                                           :data-ressource-propre="totalRessourcePropre(intervenant.heures)"
                                           :disabled="totalPayable(intervenant) == 0 "
                                           :name="'intervenant[' + intervenant.datasIntervenant.id +']'"
                                           :title="totalPayable(intervenant) == 0?'Aucune heure pré-paramétrée avec un centre de coût ne peut bénéficier d\'une demande de mise en paiement':''"
                                           checked="checked"
                                           class="checkbox-autre"
                                           type="checkbox"
                                           @change="refreshTotalConsommation()"></td>
                                <td>
                                    <i title="La fiche intervenant contient des incohérences de paiement (trop payé, etc...), une revue manuelle des paiements est nécessaire."
                                       v-if="intervenant.datasIntervenant.incoherencePaiement"
                                       class="fas fa-triangle-exclamation" style="color:#a40000;cursor: help;"></i>&nbsp;

                                    <a :href="urlIntervenant(intervenant)"
                                       target="_blank">{{
                                        intervenant.datasIntervenant.nom_usuel.toUpperCase() + ' ' + intervenant.datasIntervenant.prenom
                                    }}</a></td>
                                <td><span
                                    :title="totalRessourcePaieEtat(intervenant.heures) + ' HETD en paie état / ' + totalRessourcePropre(intervenant.heures) + ' HETD en ressource propre' "
                                    style="text-decoration:underline dotted;cursor: help;">
                                    {{ totalPayable(intervenant) }} h</span></td>
                                <td><span style="text-decoration:underline dotted;cursor: help;"
                                          title="Manque un centre de coût et/ou un domaine fonctionnel">{{
                                        totalNonPayable(intervenant)
                                    }} h</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input :value="selectedStructure" name="selectedStructure" type="hidden"/>

            <div v-if="this.intervenants.length > 0" class="mt-3">
                <button id="btn-pdmep-inprogress" class="btn btn-primary d-none" disabled type="button">
                    <span id="spinner" aria-hidden="true" class="spinner-border spinner-border-sm" role="status"></span>
                    &nbsp;Veuillez patienter...
                </button>
                <button id="btn-pdmep" :disabled="this.alertDotation" class="btn btn-primary" type="button"
                        @click="processDemandeMiseEnPaiement">
                    Enregistrer les demandes de paiement
                </button>
                <a v-if="this.canMiseEnPaiement" id="btn-mep" :href="this.urlMiseEnPaiement"
                   class="ms-2 btn btn-secondary">
                    Aller au mise en paiement
                </a>

            </div>
        </form>
    </div>


</template>

<script>


import UnicaenVue from "unicaen-vue/js/Client/unicaenVue";

export default {
    name: "DemandeMiseEnPaiementLot.vue",
    props: {
        structures: {type: Array, required: true},
        canMiseEnPaiement: {type: Boolean, required: true},
    },
    data()
    {
        return {
            selectedStructure: null,
            urlRechercheDemandeMiseEnPaiement: unicaenVue.url('paiement/demande-mise-en-paiement-lot'),
            urlProcessDemandeMiseEnPaiement: unicaenVue.url('paiement/process-demande-mise-en-paiement-lot'),
            urlMiseEnPaiement: unicaenVue.url('paiement/etat-demande-paiement'),
            permanents: [],
            vacataires: [],
            etudiants: [],
            autres: [],
            intervenants: [],
            dotation: false,
            liquidation: null,
            totalConsommationPaieEtat: 0,
            totalConsommationRessourcePropre: 0,
            totalConsommation: 0,
            alertDotation: false,


        }
    },
    computed:
        {
            haveDotation()
            {
                if (this.dotation) {
                    return this.dotation.total > 0;
                }
                return false;
            },
            alertPaieEtat()
            {
                if (this.dotation.paieEtat < this.totalConsommationPaieEtat) {
                    return 'text-danger fw-bold';
                }
                return '';
            },
            alertRessourcePropre()
            {
                if (this.dotation.ressourcePropre < this.totalConsommationRessourcePropre) {
                    return 'text-danger fw-bold';
                }

                return '';
            },


        },
    methods: {
        findDemandeMiseEnPaiement(event)
        {
            this.totalConsommationRessourcePropre = 0;
            this.totalConsommationPaieEtat = 0;
            this.dotation = null;
            this.liquidation = null;
            let form = document.getElementById('formRechercheDemandeMiseEnPaiement');
            let formData = new FormData(form);
            let btnRdmep = document.getElementById('btn-rdmep')
            let btnRdmepInProgress = document.getElementById('btn-rdmep-inprogress')
            btnRdmepInProgress.classList.remove('d-none');
            btnRdmep.classList.add('d-none');
            btnRdmep.disabled = true;

            unicaenVue.axios.post(this.urlRechercheDemandeMiseEnPaiement, formData, {}).then(response => {
                this.dispatchDatas(response.data);
                btnRdmep.disabled = false;
                btnRdmepInProgress.classList.add('d-none');
                btnRdmep.classList.remove('d-none');
                let checkboxes = document.querySelectorAll('input[type="checkbox"]');
                //On coche par défaut tous les intervenants
                checkboxes.forEach(function (element, index) {
                    element.checked = true;
                });
                unicaenVue.axios.get(unicaenVue.url('budget/get-budget-structure/:structure', {structure: this.selectedStructure})).then(response => {
                    let datas = response.data;
                    this.dotation = datas.dotation;
                    this.liquidation = datas.liquidation;
                    this.refreshTotalConsommation();

                })
            }).catch(error => {
                console.error(error);
            })
        },
        processDemandeMiseEnPaiement(event)
        {
            let form = document.getElementById('formProcessDemandeMiseEnPaiement');
            let formData = new FormData(form);
            if ((this.dotation.paieEtat >= this.totalConsommationPaieEtat && this.dotation.ressourcePropre >= this.totalConsommationRessourcePropre) || !this.haveDotation) {
                //On desactive le bouton de soumission
                let btnPdmep = document.getElementById('btn-pdmep')
                let btnPdmepInProgress = document.getElementById('btn-pdmep-inprogress')
                btnPdmepInProgress.classList.remove('d-none');
                btnPdmep.classList.add('d-none');
                btnPdmep.disabled = true;

                unicaenVue.axios.post(this.urlProcessDemandeMiseEnPaiement, formData, {}).then(response => {
                    this.findDemandeMiseEnPaiement()
                    btnPdmep.disabled = false;
                    btnPdmepInProgress.classList.add('d-none');
                    btnPdmep.classList.remove('d-none');


                }).catch(error => {
                    console.error('Error process dmep');
                })
            }
        },
        refreshTotalConsommation()
        {
            let totalPaieEtat = 0;
            let totalRessourcePropre = 0;
            let total = 0;
            totalPaieEtat = parseFloat(this.liquidation.paieEtat);
            totalRessourcePropre = parseFloat(this.liquidation.ressourcePropre);
            total += parseFloat(this.liquidation.ressourcePropre);
            total += parseFloat(this.liquidation.paieEtat);
            //On prend toutes les checkboxs
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function (element, index) {
                //uniquement si intervenant sélectionné et paie-etat
                if (element.hasAttribute('data-paie-etat') && element.checked) {
                    totalPaieEtat += parseFloat(element.getAttribute('data-paie-etat'));
                    total += parseFloat(element.getAttribute('data-paie-etat'));

                }
                //uniquement si intervenant sélectionné et ressource-propre
                if (element.hasAttribute('data-ressource-propre') && element.checked) {
                    totalRessourcePropre += parseFloat(element.getAttribute('data-ressource-propre'));
                    total += parseFloat(element.getAttribute('data-ressource-propre'));
                }


            });

            this.totalConsommationPaieEtat = totalPaieEtat.toFixed(2);
            this.totalConsommationRessourcePropre = totalRessourcePropre.toFixed(2);
            this.totalConsommation = total.toFixed(2);
            this.alertDotation = ((this.dotation.paieEtat < this.totalConsommationPaieEtat || this.dotation.ressourcePropre < this.totalConsommationRessourcePropre) && this.dotation.total > 0) ? true : false;

        },

        totalPayable(intervenant)
        {
            // Si intervenant n'est pas encore chargé
            if (!intervenant || !intervenant.datasIntervenant) {
                return 0;
            }
            let total = 0;


            if (intervenant?.datasIntervenant?.incoherencePaiement) {
                return intervenant.datasIntervenant.totalHeures.toLocaleString('fr-FR', {maximumFractionDigits: 2});
            }
            intervenant.heures.forEach((item, index) => {
                if (item.centreCout.code != '') {
                    if (item.missionId != '' || item.serviceRefId != '') {
                        if (item.domaineFonctionnel.code != '') {
                            total += item.heuresAPayer;

                        }
                    } else {
                        total += item.heuresAPayer;
                    }

                }
            })
            return total.toLocaleString('fr-FR', {maximumFractionDigits: 2});

        },
        totalNonPayable(intervenant)
        {
            // Si intervenant n'est pas encore chargé
            if (!intervenant || !intervenant.datasIntervenant) {
                return 0;
            }

            let total = 0;
            if (intervenant?.datasIntervenant?.incoherencePaiement) {
                return total.toLocaleString('fr-FR', {maximumFractionDigits: 2});
            }

            intervenant.heures.forEach((item, index) => {
                if (item.centreCout.code == '') {
                    total += item.heuresAPayer;
                } else {
                    if (item.missionId != '' || item.serviceRefId != '') {
                        if (item.domaineFonctionnel.code == '') {
                            total += item.heuresAPayer;

                        }
                    }
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
            this.intervenants = [];


            for (const [index, intervenant] of Object.entries(datas)) {
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
            }

        },

        toggleCheckbox(event)
        {
            //on récupere toutes les checkbox que l'on doit traiter
            let checkbox = Array.from(document.getElementsByClassName(event.target.className));
            if (event.target.checked) {
                checkbox.forEach(function (element, index) {
                    element.checked = true;
                });
            } else {
                checkbox.forEach(function (element, index) {
                    element.checked = false;
                });
            }
            this.refreshTotalConsommation();

        },

        urlIntervenant(intervenant)
        {
            return unicaenVue.url('intervenant/code::intervenantCode/mise-en-paiement/demande', {
                intervenantCode: intervenant.datasIntervenant.code,
            })

        },
        formattedHETD(hetd)
        {
            return Util.formattedHeures(hetd, false);
        }


    }
}
</script>