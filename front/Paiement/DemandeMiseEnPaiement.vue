<template>

    <div v-for="(structure, code) in datasServiceAPayer ">
        <div class="accordion-item">
            <h2 id="dmep-vacataires-heading" class="accordion-header">
                <button aria-controls="dmep-vacataires-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-vacataires-collapse"
                        data-bs-toggle="collapse"
                        type="button">
                    {{ code + ' - ' + structure.libelle }}
                </button>

            </h2>


            <div id="dmep-vacataires-collapse" aria-labelledby="dmep-vacataires-heading" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div v-for="(etape, codeEtape) in structure.etapes">
                        <div v-for="(enseignement,codeEnseignement) in etape.enseignements">
                            <div class="cartridge gray bordered" style="padding-bottom: 5px">
                                <span>{{ etape.libelle }}</span>
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
                                                            <th scope="col" style="font-size:12px;">Heures</th>
                                                            <th scope="col" style="font-size:12px;">Centre cout</th>
                                                            <th scope="col" style="font-size:12px;">Statut</th>
                                                            </thead>
                                                            <tbody>
                                                            <tr v-for="(value,id) in typeHeure.heures">
                                                                <td v-if="value.heuresDemandees != 0 ">{{ value.heuresAPayer }} hetd</td>
                                                                <td v-if="value.heuresDemandees == 0 ">
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
                                                                            style="width: 40px;"
                                                                            type="number"
                                                                        />
                                                                        <span class="input-group-text" style="font-size:12px;">hetd(s) restantes</span>
                                                                    </div>
                                                                </td>
                                                                <!--<td>{{ value.centreCout.libelle }}</td>-->
                                                                <td v-if="value.heuresDemandees == 0 ">
                                                                    <select :id="'centreCout-' + codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure"
                                                                            name="">
                                                                        <option value="2907">PAYE M1 MAE EVREUX</option>
                                                                    </select>
                                                                </td>
                                                                <td v-if="value.heuresDemandees != 0 ">
                                                                    {{ value.centreCout.libelle }}
                                                                </td>
                                                                <td style="font-size:12px;">
                                                                    {{ heuresStatut(value) }}
                                                                    <span
                                                                        v-if="value.heuresAPayer != value.heuresPayees &&  value.heuresAPayer == value.heuresDemandees">
                                                                        <button class="btn btn-danger"
                                                                                type="button" @click="this.supprimerDemandeMiseEnPaiement(value.mepId)">Supprimer</button>
                                                                    </span>
                                                                    <span
                                                                        v-if="value.heuresDemandees == 0">
                                                                        <button class="btn btn-success" type="button"
                                                                                @click="this.ajouterDemandeMiseEnPaiement(codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure)">Demander</button>
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
                                                    <td>21 HETD</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>


import UnicaenVue from "unicaen-vue/js/Client/unicaenVue";

export default {
    name: "DemandeMiseEnPaiement.vue",
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
    computed:
        {},
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
        heuresStatut(value)
        {
            if (value.heuresAPayer == value.heuresPayees) {
                return 'Heures payées';
            }
            if (value.heuresAPayer == value.heuresDemandees) {
                return 'Paiement demandé';
            }
            if (value.heuresDemandees == 0) {
                return 'Paiement à demander';
            }
            return 'indetermine';
        },
        supprimerDemandeMiseEnPaiement(id)
        {
            unicaenVue.axios.get(unicaenVue.url('paiement/:intervenant/supprimer-demande/:dmep', {intervenant: this.intervenant, dmep: id}))
                .then(response => {
                    this.findServiceAPayer();
                })
                .catch(error => {
                    console.error(error);
                })
        },
        ajouterDemandeMiseEnPaiement(id)
        {
            let inputHeure = document.getElementById('heures-' + id);
            let inputCentreCout = document.getElementById('centreCout-' + id);
            let heureADemander = inputHeure.value;
            let centreCoutId = inputCentreCout.value;

            let typeHeureId = (inputHeure.hasAttribute('data-type-heures-id') ? inputHeure.getAttribute('data-type-heures-id') : '');
            let formuleResServiceId = (inputHeure.hasAttribute('data-formule-res-service-id') ? inputHeure.getAttribute('data-formule-res-service-id') : '');
            let formuleResServiceRefId = (inputHeure.hasAttribute('data-formule-res-service-ref-id') ? inputHeure.getAttribute('data-formule-res-service-ref-id') : '');
            let missionId = (inputHeure.hasAttribute('data-mission-id') ? inputHeure.getAttribute('data-mission-id') : '');

            var datas = new FormData();
            datas.append('heures', heureADemander);
            datas.append('typeHeuresId', typeHeureId);
            datas.append('formuleResServiceId', formuleResServiceId);
            datas.append('formuleResServiceRefId', formuleResServiceRefId);
            datas.append('centreCoutId', centreCoutId);
            datas.append('missionId', missionId);
            console.log(datas);

            unicaenVue.axios.post(unicaenVue.url('paiement/:intervenant/ajouter-demande', {intervenant: this.intervenant}), datas)
                .then(response => {
                    this.findServiceAPayer();
                })
                .catch(error => {
                    console.error(error);
                })
        }
    },
    mounted()
    {
        this.findServiceAPayer();
    }
}
</script>