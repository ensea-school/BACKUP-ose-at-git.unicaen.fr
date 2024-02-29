<template>
    <div :id="'demande-mise-en-paiement-' + datas.code" class="accordion-item">
        <h2 :id="'dmep-heading-' + datas.code" class="accordion-header ">
            <button :aria-controls="'dmep-collapse-' + datas.code" :data-bs-target="'#dmep-collapse-' + datas.code" aria-expanded="true"
                    class="accordion-button bg-light"
                    data-bs-toggle="collapse"
                    type="button">
                {{ datas.code + ' - ' + datas.libelle }}
            </button>

        </h2>

        <div :id="'dmep-collapse-' + datas.code" :aria-labelledby="'dmep-heading-' + datas.code" class="accordion-collapse collapse show">
            <div class="accordion-body">
                <div v-if="this.dotationPaieEtat + dotationRessourcesPropres > 0">
                    <!--Budget-->
                    <div class="cartridge gray bordered" style="padding-bottom: 5px;margin-bottom:20px;">
                        <span>Budget</span>
                    </div>
                    <div class="container">
                        <table class="table table-bordered caption-top">
                            <thead class="table-light">
                            <tr>
                                <th class="fw-bold" scope="col">Paie etat</th>
                                <th class="fw-bold" scope="col">Ressources propres</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                                <td>
                                    <div class="progress position-relative bg-secondary" style="height: 30px;">
                                        <span class="position-absolute top-50 start-50 translate-middle" style="color:white;">{{
                                                this.consommationPaieEtat + ' sur ' + this.dotationPaieEtat
                                            }} HETD</span>
                                        <div :aria-valuemax="this.dotationPaieEtat"
                                             :aria-valuenow="this.consommationPaieEtat"
                                             :style="'width:' + this.pourcentagePaieEtat + '%;'"
                                             :title="this.pourcentagePaieEtat + '%'"
                                             aria-valuemin="0"
                                             class="progress-bar progress-bar-striped bg-success"
                                             role="progressbar">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress position-relative bg-secondary" style="height: 30px;">
                                        <span class="position-absolute top-50 start-50 translate-middle" style="color:white;">{{
                                                this.consommationRessourcesPropres + ' sur ' + this.dotationRessourcesPropres
                                            }} HETD</span>
                                        <div :aria-valuemax="this.dotationRessourcesPropres"
                                             :aria-valuenow="this.consommationRessourcesPropres"
                                             :style="'width:' + this.pourcentageRessourcePropre + '%;'"
                                             :title="this.pourcentageRessourcePropre + '%'"
                                             aria-valuemin="0"
                                             class="progress-bar progress-bar-striped bg-success"
                                             role="progressbar">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-for="(etape, codeEtape) in datas.etapes">
                    <div v-for="(enseignement,codeEnseignement) in etape.enseignements">
                        <div class="cartridge gray bordered" style="padding-bottom: 5px">
                            <span>Enseignement</span>
                            <span v-html="shorten(etape.libelle, 50)"></span>
                            <span>{{ enseignement.libelle }}</span>
                        </div>
                        <div class="container">
                            <div class="row">
                                <template v-for="(typeHeure, codeTypeHeure) in enseignement.typeHeure">
                                    <div class="col-12">
                                        <table class="table mt-3 table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th colspan="2">{{ typeHeure.libelle }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <table class="table table-sm ">
                                                        <thead>
                                                        <th scope="col" style="width:20%;font-size:12px;">Heures</th>
                                                        <th scope="col" style="width:40%;font-size:12px;">Centre de cout</th>
                                                        <th scope="col" style="width:25%;font-size:12px;">Etat</th>
                                                        <th style="width:15%;font-size:12px;"></th>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(value,id) in typeHeure.heures" class="detailHeure">
                                                            <td v-if="value.heuresDemandees != 0 " style="width:20%;">{{ Number(value.heuresAPayer) }}
                                                                hetd
                                                            </td>
                                                            <td v-if="value.heuresDemandees == 0 " style="width:20%;">
                                                                <div class="input-group col-1">
                                                                    <input
                                                                        :id="'heures-' + codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure"
                                                                        :data-domaine-fonctionnel-id="value.domaineFonctionnelId"
                                                                        :data-formule-res-service-id="value.formuleResServiceId"
                                                                        :data-formule-res-service-ref-id="value.formuleResServiceRefId"
                                                                        :data-mission-id="value.missionId"
                                                                        :data-type-heures-id="value.typeHeureId"
                                                                        :max="value.heuresAPayer"
                                                                        :value="value.heuresAPayer"
                                                                        class="form-control form-control-sm"
                                                                        min="0"
                                                                        type="number"
                                                                    />
                                                                    <span class="input-group-text" style="font-size:12px;">hetd(s)</span>
                                                                </div>
                                                            </td>
                                                            <!--<td>{{ value.centreCout.libelle }}</td>-->
                                                            <td v-if="value.heuresDemandees == 0 ">
                                                                <select :id="'centreCout-' + codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure"
                                                                        class="selectpicker"
                                                                        data-live-search="true"
                                                                        name="centreCout">
                                                                    <option value="">{{
                                                                            notValueCentreCoutValue(datas.centreCoutPaiement, value.typeHeureCode)
                                                                        }}
                                                                    </option>
                                                                    <optgroup
                                                                        v-for="group in filtrerCentresCouts(datas.centreCoutPaiement,value.typeHeureCode)"
                                                                        :key="group.group"
                                                                        :label="group.group">
                                                                        <option
                                                                            v-for="item in group.child"
                                                                            :key="item.value"
                                                                            :data-paie-etat="item.paieEtat"
                                                                            :data-ressources-propres="item.ressourcesPropres"
                                                                            :selected="item.centreCoutId==value.centreCout.centreCoutId"
                                                                            :value="item.centreCoutId">
                                                                            {{ item.centreCoutCode + ' - ' + item.centreCoutLibelle }}
                                                                        </option>

                                                                    </optgroup>
                                                                </select>

                                                            </td>
                                                            <td v-if="value.heuresDemandees != 0 "
                                                                v-html="shorten(value.centreCout.code + ' - ' + value.centreCout.libelle, 30)">
                                                            </td>
                                                            <td v-html="heuresStatutToString(value)">

                                                            </td>
                                                            <td style="font-size:12px;">
                                                                <span
                                                                    v-if="value.heuresAPayer != value.heuresPayees &&  value.heuresAPayer == value.heuresDemandees">
                                                                    <button :id="'remove-' + value.mepId"
                                                                            class="btn btn-danger"
                                                                            type="button" @click="this.supprimerDemandeMiseEnPaiement(value.mepId)">
                                                                        <u-icon id="action" name="trash" style="color:white;"/>
                                                                        <u-icon id="waiting" name="spin" rotate="right" style="color:white;display:none;"/>
                                                                    </button>
                                                                </span>
                                                                <span
                                                                    v-if="value.heuresDemandees == 0">
                                                                    <button :id="'add-' + codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure"
                                                                            class="btn btn-primary"
                                                                            type="button"
                                                                            @click="this.ajouterDemandeMiseEnPaiement(codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure)">
                                                                            <u-icon id="action" name="plus"/>
                                                                            <u-icon id="waiting" name="spin" rotate="right" style="display:none;"/>

                                                                    </button>
                                                                </span>

                                                            </td>
                                                        </tr>
                                                        </tbody>

                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr class="table-light">
                                                <th scope="row">Total</th>
                                                <td>{{ totalHeure(typeHeure.heures) }} hetd</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <!--FONCTION REFERENTIEL-->
                <div v-for="(fonction, codeFonction) in datas.fonctionsReferentiels">
                    <div class="cartridge gray bordered" style="padding-bottom: 5px">
                        <span>Référentiel</span>
                        <span>{{ fonction.libelle }}</span>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <table class="table mt-3 table-bordered">

                                    <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <table class="table table-sm ">
                                                <thead>
                                                <th scope="col" style="width:10%;font-size:12px;">Heures</th>
                                                <th scope="col" style="width:25%;font-size:12px;">Centre de cout</th>
                                                <th scope="col" style="width:25%;font-size:12px;">Domaine fonctionnel</th>
                                                <th scope="col" style="width:20%;font-size:12px;">Etat</th>
                                                <th style="width:15%;font-size:12px;"></th>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(value,id) in fonction.heures" class="detailHeure">
                                                    <td v-if="value.heuresDemandees != 0 " style="width:10%;">{{ value.heuresAPayer }} hetd</td>
                                                    <td v-if="value.heuresDemandees == 0 " style="width:20%;">
                                                        <div class="input-group col-1">
                                                            <input
                                                                :id="'heures-' + codeFonction"
                                                                :data-domaine-fonctionnel-id="value.domaineFonctionnelId"
                                                                :data-formule-res-service-id="value.formuleResServiceId"
                                                                :data-formule-res-service-ref-id="value.formuleResServiceRefId"
                                                                :data-mission-id="value.missionId"
                                                                :data-type-heures-id="value.typeHeureId"
                                                                :max="value.heuresAPayer"
                                                                :value="value.heuresAPayer"
                                                                class="form-control form-control-sm"
                                                                min="0"
                                                                style="width: 40px;"
                                                                type="number"
                                                            />
                                                            <span class="input-group-text" style="font-size:12px;">hetd(s)</span>
                                                        </div>
                                                    </td>
                                                    <td v-if="value.heuresDemandees == 0 ">
                                                        <select :id="'centreCout-' + codeFonction"
                                                                class="selectpicker"
                                                                data-live-search="true"
                                                                name="centreCout">
                                                            <option value="">Aucun centre de cout</option>
                                                            <optgroup
                                                                v-for="group in filtrerCentresCouts(datas.centreCoutPaiement,'referentiel')"
                                                                :key="group.group"
                                                                :label="group.group">
                                                                <option v-for="item in group.child"
                                                                        :key="item.value"
                                                                        :data-paie-etat="item.paieEtat"
                                                                        :data-ressources-propres="item.ressourcesPropres"
                                                                        :selected="item.centreCoutId == value.centreCout.centreCoutId"
                                                                        :value="item.centreCoutId">
                                                                    {{ item.centreCoutCode + ' - ' + item.centreCoutLibelle }}
                                                                </option>

                                                            </optgroup>
                                                        </select>
                                                    </td>
                                                    <td v-if="value.heuresDemandees != 0 "
                                                        v-html="shorten(value.centreCout.code + ' - ' + value.centreCout.libelle, 20)">
                                                    </td>
                                                    <td v-if="value.heuresDemandees == 0 ">
                                                        <select :id="'domaineFonctionnel-' + codeFonction"
                                                                class="selectpicker"
                                                                data-live-search="true"
                                                                name="centreCout">
                                                            <option value="">Aucun domaine fonctionnel</option>
                                                            <option v-for="item in datas.domaineFonctionnelPaiement"
                                                                    :selected="item.domaineFonctionnelId == value.domaineFonctionnel.domaineFonctionnelId"
                                                                    :value="item.domaineFonctionnelId">
                                                                {{ item.domaineFonctionnelLibelle }}
                                                            </option>


                                                        </select>
                                                    </td>
                                                    <td v-if="value.heuresDemandees != 0 ">
                                                        {{ value.domaineFonctionnel.libelle }}
                                                    </td>
                                                    <td v-html="heuresStatutToString(value)">
                                                    </td>
                                                    <td style="font-size:12px;">
                                                                <span
                                                                    v-if="value.heuresAPayer != value.heuresPayees &&  value.heuresAPayer == value.heuresDemandees">
                                                                    <button :id="'remove-' + value.mepId"
                                                                            class="btn btn-danger"
                                                                            type="button" @click="this.supprimerDemandeMiseEnPaiement(value.mepId)">
                                                                        <u-icon id="action" name="trash" style="color:white;"/>
                                                                        <u-icon id="waiting" name="spin" rotate="right" style="color:white;display:none;"/>
                                                                    </button>
                                                                </span>
                                                        <span
                                                            v-if="value.heuresDemandees == 0">
                                                                    <button :id="'add-' + codeFonction"
                                                                            class="btn btn-primary"
                                                                            type="button"
                                                                            @click="this.ajouterDemandeMiseEnPaiement(codeFonction)">
                                                                            <u-icon id="action" name="plus"/>
                                                                            <u-icon id="waiting" name="spin" rotate="right" style="display:none;"/>

                                                                    </button>
                                                        </span>

                                                    </td>
                                                </tr>
                                                </tbody>


                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr class="table-light">
                                        <th scope="row">Total</th>
                                        <td>{{ totalHeure(fonction.heures) }} hetd</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!--MISSIONS-->
                <div v-for="mission in datas.missions">
                    <div class="cartridge gray bordered" style="padding-bottom: 5px">
                        <span>Mission</span>
                        <span>{{ mission.libelle }}</span>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-12">

                                <table class="table mt-3 table-bordered">

                                    <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <table class="table table-sm ">
                                                <thead>
                                                <th scope="col" style="width:10%;font-size:12px;">Heures</th>
                                                <th scope="col" style="width:25%;font-size:12px;">Centre de cout</th>
                                                <th scope="col" style="width:25%;font-size:12px;">Domaine fonctionnel</th>
                                                <th scope="col" style="width:20%;font-size:12px;">Etat</th>
                                                <th style="width:15%;font-size:12px;"></th>
                                                </thead>
                                                <tbody>

                                                <tr v-for="(value,id) in mission.heures" class="detailHeure">
                                                    <td v-if="value.heuresDemandees != 0 " style="width:10%;">{{ value.heuresAPayer }} hetd</td>
                                                    <td v-if="value.heuresDemandees == 0 " style="width:20%;">
                                                        <div class="input-group col-1">
                                                            <input
                                                                :id="'heures-' + mission.missionId"
                                                                :data-domaine-fonctionnel-id="value.domaineFonctionnelId"
                                                                :data-formule-res-service-ref-id="value.formuleResServiceRefId"
                                                                :data-mission-id="value.missionId"
                                                                :data-type-heures-id="value.typeHeureId"
                                                                :max="value.heuresAPayer"
                                                                :value="value.heuresAPayer"
                                                                class="form-control form-control-sm"
                                                                min="0"
                                                                style="width: 40px;"
                                                                type="number"
                                                            />
                                                            <span class="input-group-text" style="font-size:12px;">hetd(s)</span>
                                                        </div>
                                                    </td>
                                                    <td v-if="value.heuresDemandees == 0 ">
                                                        <select :id="'centreCout-' + mission.missionId" class="selectpicker"
                                                                data-live-search="true"
                                                                name="centreCout"
                                                                @change="enabledPaiement(mission.missionId,'mission')">
                                                            <option value="">Aucun centre de cout</option>
                                                            <optgroup
                                                                v-for="group in filtrerCentresCouts(datas.centreCoutPaiement,'mission')"
                                                                :key="group.group"
                                                                :label="group.group">
                                                                <option v-for="item in group.child"
                                                                        :key="item.value"
                                                                        :data-paie-etat="item.paieEtat"
                                                                        :data-ressources-propres="item.ressourcesPropres"
                                                                        :selected="item.centreCoutId == value.centreCout.centreCoutId"
                                                                        :value="item.centreCoutId"
                                                                >
                                                                    {{ item.centreCoutCode + ' - ' + item.centreCoutLibelle }}
                                                                </option>

                                                            </optgroup>
                                                        </select>
                                                    </td>
                                                    <td v-if="value.heuresDemandees != 0 "
                                                        v-html="shorten(value.centreCout.code + ' - ' + value.centreCout.libelle, 20)">
                                                    </td>
                                                    <td v-if="value.heuresDemandees == 0 ">
                                                        <select :id="'domaineFonctionnel-' + mission.missionId"
                                                                class="selectpicker"
                                                                data-live-search="true"
                                                                name="centreCout"
                                                                @change="enabledPaiement(mission.missionId,'mission')">
                                                            <option value="">Aucun domaine fonctionnel</option>
                                                            <option v-for="item in datas.domaineFonctionnelPaiement"
                                                                    :selected="item.domaineFonctionnelId == value.domaineFonctionnel.domaineFonctionnelId"
                                                                    :value="item.domaineFonctionnelId">
                                                                {{ item.domaineFonctionnelLibelle }}
                                                            </option>


                                                        </select>
                                                    </td>
                                                    <td v-if="value.heuresDemandees != 0 ">
                                                        {{ value.domaineFonctionnel.libelle }}
                                                    </td>
                                                    <td v-html="heuresStatutToString(value)">
                                                    </td>
                                                    <td style="font-size:12px;">
                                                                <span
                                                                    v-if="value.heuresAPayer != value.heuresPayees &&  value.heuresAPayer == value.heuresDemandees">
                                                                    <button :id="'remove-' + value.mepId"
                                                                            class="btn btn-danger"
                                                                            type="button" @click="this.supprimerDemandeMiseEnPaiement(value.mepId)">
                                                                        <u-icon id="action" name="trash" style="color:white;"/>
                                                                        <u-icon id="waiting" name="spin" rotate="right" style="color:white;display:none;"/>
                                                                    </button>
                                                                </span>
                                                        <span
                                                            v-if="value.heuresDemandees == 0">
                                                                    <button :id="'add-' + mission.missionId"
                                                                            class="btn btn-primary"
                                                                            type="button"
                                                                            @click="this.ajouterDemandeMiseEnPaiement(mission.missionId)">
                                                                            <u-icon id="action" name="plus"/>
                                                                            <u-icon id="waiting" name="spin" rotate="right" style="display:none;"/>
                                                                    </button>
                                                                </span>

                                                    </td>
                                                </tr>
                                                </tbody>


                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr class="table-light">
                                        <th scope="row">Total</th>
                                        <td>{{ totalHeure(mission.heures) }} hetd</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <div style="background-color:#fbfbfb;padding:10px;padding-right:40px;text-align:right;">
                <button :id="'add-all-' + datas.code"
                        class="btn btn-primary"
                        type="button"
                        @click="demanderToutesLesHeuresEnPaiement(datas.code, datas.libelle)">
                    <u-icon id="action" name="square-plus" style="color:white;"/>
                    <u-icon id="waiting" name="spin" rotate="right" style="display:none;"/>
                    DEMANDER TOUS LES PAIEMENTS POUR {{ datas.libelle }}
                </button>
            </div>
        </div>
    </div>


</template>

<script>

export default {

    name: "DemandeMiseEnPaiementStructure",
    props: {
        datas: {required: true},
        intervenant: {required: true},
    },
    data()
    {
        return {
            dotationPaieEtat: this.datas.budget.dotation.paieEtat,
            dotationRessourcesPropres: this.datas.budget.dotation.ressourcePropre,
            consommationPaieEtat: this.datas.budget.liquidation.paieEtat,
            consommationRessourcesPropres: this.datas.budget.liquidation.ressourcePropre,


        }
    },
    watch: {
        datas: function () {
            this.dotationPaieEtat = this.datas.budget.dotation.paieEtat;
            this.dotationRessourcesPropres = this.datas.budget.dotation.ressourcePropre;
            this.consommationPaieEtat = this.datas.budget.liquidation.paieEtat;
            this.consommationRessourcesPropres = this.datas.budget.liquidation.ressourcePropre;
        },

    },
    computed:
        {
            pourcentagePaieEtat()
            {
                return Math.round((this.consommationPaieEtat / this.dotationPaieEtat) * 100);
            },
            pourcentageRessourcePropre()
            {
                return Math.round((this.consommationRessourcesPropres / this.dotationRessourcesPropres) * 100);
            }
        },
    methods: {
        heuresStatutToString(value)
        {

            if (value.heuresAPayer == value.heuresPayees) {
                return '<span style="font-size:12px;line-height:20px;" class="badge bg-success">Paiement effectué</span>';
            }
            if (value.heuresAPayer == value.heuresDemandees) {
                return '<span style="font-size:12px;line-height:20px;" class="badge bg-secondary text-dark">Paiement demandé</span>';
            }
            if (value.heuresDemandees == 0) {
                return '<span style="font-size:12px;line-height:20px;" class="badge bg-light text-dark">A payer</span>';
            }
            if (value.heuresPayees > value.heuresAPayer) {
                let diff = value.heuresPayees - value.heuresAPayer;
                return '<span style="font-size:12px;line-height:20px;" class="badge bg-danger">Paiement effectué - ' + diff + ' hetd de trop payé </span>';
            }
            return 'indetermine';
        },
        supprimerDemandeMiseEnPaiement(id)
        {
            //On récupere le bouton d'ajout
            this.btnToggle('remove-' + id);
            unicaenVue.axios.get(unicaenVue.url('paiement/:intervenant/supprimer-demande/:dmep', {intervenant: this.intervenant, dmep: id}))
                .then(response => {
                    this.$emit('refresh');
                    setTimeout(() => {
                        this.btnToggle('remove-' + id);
                    }, 2500);
                })
                .catch(error => {
                    this.$emit('refresh');
                    setTimeout(() => {
                        this.btnToggle('remove-' + id);
                    }, 2500);
                })
        },
        disabledPaiement(value)
        {
            if (value.missionId != '' || value.formuleResServiceRefId != '') {
                if (value.centreCoutId && value.domaineFonctionnel) {
                    return false;
                } else {
                    return true;
                }
            }
            return false;
        },
        enabledPaiement(id, type)
        {
            if (type == 'mission') {
                let btnAdd = document.getElementById('add-' + id);
                let centreCoutId = document.getElementById('centreCout-' + id).value;
                let domaineFonctionnelId = document.getElementById('domaineFonctionnel-' + id).value;
                if (centreCoutId != '' && domaineFonctionnelId != '') {
                    btnAdd.disabled = false;
                } else {
                    btnAdd.disabled = true;
                }

            }

        },


        ajouterDemandeMiseEnPaiement(id)
        {

            //On récupere le bouton d'ajout
            this.btnToggle('add-' + id);
            let options = {animation: true, delay: 15000, autohide: true};
            let inputHeure = document.getElementById('heures-' + id);
            let inputCentreCout = document.getElementById('centreCout-' + id);
            let inputDomaineFonctionnel = document.getElementById('domaineFonctionnel-' + id);
            let heureADemander = Number(inputHeure.value);
            let heureADemanderMax = Number(inputHeure.getAttribute('max'));
            let domaineFonctionnelId = (inputDomaineFonctionnel) ? inputDomaineFonctionnel.value : '';
            let typeHeureId = (inputHeure.hasAttribute('data-type-heures-id') ? inputHeure.getAttribute('data-type-heures-id') : '');
            let formuleResServiceId = (inputHeure.hasAttribute('data-formule-res-service-id') ? inputHeure.getAttribute('data-formule-res-service-id') : '');
            let formuleResServiceRefId = (inputHeure.hasAttribute('data-formule-res-service-ref-id') ? inputHeure.getAttribute('data-formule-res-service-ref-id') : '');
            let missionId = (inputHeure.hasAttribute('data-mission-id') ? inputHeure.getAttribute('data-mission-id') : '');

            let centreCoutId = inputCentreCout.value;
            let ressourcesPropres = inputCentreCout.options[inputCentreCout.selectedIndex].getAttribute('data-ressources-propres');
            let paieEtat = inputCentreCout.options[inputCentreCout.selectedIndex].getAttribute('data-paie-etat');
            console.log("Ressource Propres => " + ressourcesPropres);
            console.log("Paie etat => " + paieEtat);
            //Si centre de cout non sélectionné
            if (centreCoutId == '') {
                unicaenVue.flashMessenger.toast("Vous devez sélectionner un centre de coût pour demander la mise en paiement de ces heures", 'error', options)
                this.btnToggle('add-' + id);
                return false;

            }
            //Si le nombre d'heure demandées est supérieur au nombre d'heures maximum pour cette ligne
            if (heureADemander > 0 && heureADemander > heureADemanderMax) {
                unicaenVue.flashMessenger.toast("Demande de mise en paiement impossible, vous demandez " + heureADemander + " hetd alors que vous pouvez demander maximum " + heureADemanderMax + " hetd", 'error', options);
                this.btnToggle('add-' + id);
                return false;
            }
            //Si je suis sur une demande de mise en paiement avec des fonds paie etat
            if (paieEtat == 1 && this.dotationPaieEtat > 0) {

                let solde = this.dotationPaieEtat - (this.consommationPaieEtat + heureADemander);
                if (solde <= 0) {
                    unicaenVue.flashMessenger.toast("Demande de mise en paiement impossible manque de dotation 'paie etat' pour ces heures", 'error', options)
                    this.btnToggle('add-' + id);
                    return false;
                }
            }
            //Si je suis sur une demande de mise en paiement avec des fonds ressources propres
            if (ressourcesPropres == 1 && this.dotationRessourcesPropres > 0) {
                let solde = this.dotationRessourcesPropres - (this.consommationRessourcesPropres + heureADemander);
                if (solde <= 0) {
                    unicaenVue.flashMessenger.toast("Demande de mise en paiement impossible manque de dotation 'ressources propres' pour ces heures", 'error', options)
                    this.btnToggle('add-' + id);
                    return false;
                }
            }


            var datas = [];
            let demande = {
                heures: heureADemander,
                centreCoutId: centreCoutId,
                typeHeuresId: typeHeureId,
                formuleResServiceId: formuleResServiceId,
                formuleResServiceRefId: formuleResServiceRefId,
                domaineFonctionnelId: domaineFonctionnelId,
                missionId: missionId,
                structureId: this.datas.id,
            }
            datas.push(demande);

            //Si volontairement on passe 0 heure à demander ou si on demande plus d'heures que le maximum possible pour cette ligne

            unicaenVue.axios.post(unicaenVue.url('paiement/:intervenant/ajouter-demande', {intervenant: this.intervenant}), datas)
                .then(response => {
                    this.$emit('refresh');
                    setTimeout(() => {
                        this.btnToggle('add-' + id);
                    }, 2500);
                })
                .catch(error => {
                    console.error(error);
                })




        },
        demanderToutesLesHeuresEnPaiement(codeStructure, libelleStructure)
        {
            //On récupere le bouton d'ajout
            this.btnToggle('add-all-' + codeStructure);
            let datas = [];
            let parent = document.getElementById("demande-mise-en-paiement-" + codeStructure);
            let demandesMiseEnPaiement = parent.getElementsByTagName("tr");
            for (var i = 0; i < demandesMiseEnPaiement.length; i++) {
                //Si j'ai un champs input dans une des lignes de tableau j'ai une demande de mise en paiement à faire
                if (demandesMiseEnPaiement[i].getElementsByTagName('input').length > 0 && demandesMiseEnPaiement[i].classList.contains('detailHeure')) {
                    let inputHeure = demandesMiseEnPaiement[i].getElementsByTagName('input')[0];
                    //Si le select de centre de cout à une valeur alors je peux faire la demande de mise en paiement
                    let selectCentreCout = demandesMiseEnPaiement[i].getElementsByTagName('select')[0];
                    let inputDomaineFonctionnel = demandesMiseEnPaiement[i].getElementsByTagName('select')[1];
                    let heureADemander = Number(inputHeure.value);
                    let heureADemanderMax = Number(inputHeure.getAttribute('max'));
                    //Si volontairement on passe 0 heure à demander ou si on demande plus d'heures que le maximum possible pour cette ligne
                    if (heureADemander <= 0 || heureADemander > heureADemanderMax) {
                        console.warn("Le nombre d'heures demandées en paiement n'est pas situé entre le max et min possible.");
                    } else {
                        let centreCoutId = selectCentreCout.value;
                        let typeHeureId = (inputHeure.hasAttribute('data-type-heures-id') ? inputHeure.getAttribute('data-type-heures-id') : '');
                        let formuleResServiceId = (inputHeure.hasAttribute('data-formule-res-service-id') ? inputHeure.getAttribute('data-formule-res-service-id') : '');
                        let formuleResServiceRefId = (inputHeure.hasAttribute('data-formule-res-service-ref-id') ? inputHeure.getAttribute('data-formule-res-service-ref-id') : '');
                        let missionId = (inputHeure.hasAttribute('data-mission-id') ? inputHeure.getAttribute('data-mission-id') : '');
                        let domaineFonctionnelId = (inputDomaineFonctionnel) ? inputDomaineFonctionnel.value : '';
                        let demande = {
                            heures: heureADemander,
                            centreCoutId: centreCoutId,
                            typeHeuresId: typeHeureId,
                            formuleResServiceId: formuleResServiceId,
                            formuleResServiceRefId: formuleResServiceRefId,
                            domaineFonctionnelId: domaineFonctionnelId,
                            missionId: missionId,
                            structureId: this.datas.id,
                        }
                        datas.push(demande);
                    }

                }

            }

            unicaenVue.axios.post(unicaenVue.url('paiement/:intervenant/all-demande', {intervenant: this.intervenant}), datas)
                .then(response => {
                    this.$emit('refresh');
                    setTimeout(() => {
                        this.btnToggle('add-all-' + codeStructure);
                    }, 2500);

                })
                .catch(error => {
                    this.$emit('refresh');
                    setTimeout(() => {
                        this.btnToggle('add-all-' + codeStructure);
                    }, 2500);
                })
        },
        filtrerCentresCouts(centresCouts, typeHeures)
        {
            /*
            * Méthode permettant de filtrer les centres coûts disponibles par rapport
            * aux types d'heures à payer (fi, fa, fc etc...)
            * */


            let centresCoutesFiltered = [];
            for (var eotp in centresCouts) {
                let group = eotp;
                let child = [];
                centresCouts[eotp].forEach(function (centreCout, index) {
                    if (centreCout[typeHeures] == 1) {
                        child.push(centreCout);
                    }
                })
                if (child.length != 0) {
                    centresCoutesFiltered.push({group: group, child});
                }
            }


            return centresCoutesFiltered;
        },
        notValueCentreCoutValue(centresCouts, typeHeures)
        {
            let values = this.filtrerCentresCouts(centresCouts, typeHeures)
            if (values.length != 0) {
                return "Choisir un centre de coût";
            } else {
                return "Aucun centre de coût disponible demande de paiement impossible";
            }

        },
        totalHeure(heures)
        {
            let total = 0;
            for (var heure in heures) {
                total += Number(heures[heure].heuresAPayer);
            }
            return total;
        },
        shorten(chaine, length = 20)
        {
            if (chaine.length > length) {

                var centreCout = '<span title="' + chaine + '"';
                centreCout += 'data-bs-placement="top" data-bs-toggle="tooltip">';
                centreCout += chaine.substring(0, length) + "...";
                centreCout += '</span>'
                return centreCout;
            } else {
                return chaine;
            }
        },
        btnToggle(id)
        {
            let btn = document.getElementById(id);
            if (btn.disabled) {
                btn.disabled = false;
                btn.querySelector('#waiting').style.display = 'none';
                btn.querySelector('#action').style.display = 'inline-block';
            } else {
                btn.disabled = true;
                btn.querySelector('#waiting').style.display = 'inline-block';
                btn.querySelector('#action').style.display = 'none';
            }

            return btn;
        }


    },



}

</script>