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
                                                            <th>Action</th>
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
                                                                <td v-if="value.heuresDemandees == 0 " id="test">
                                                                    <select :id="'centreCout-' + codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure"
                                                                            v-model="selected"
                                                                            class="selectpicker"
                                                                            data-live-search="true"
                                                                            name="centreCout">
                                                                        <optgroup
                                                                            v-for="(group, groupName) in prepareCentresCouts(structure.centreCoutPaiement,value)"
                                                                            :key="groupName"
                                                                            :label="groupName">
                                                                            <option v-for="item in group" :key="item.value" :value="item.centreCoutId">
                                                                                {{ item.centreCoutLibelle + ' - ' + item.centreCoutCode }}
                                                                            </option>

                                                                        </optgroup>
                                                                    </select>
                                                                    <!--                                                                    <select
                                                                                                                                            id="teste"
                                                                                                                                            class="selectpicker"
                                                                                                                                            data-live-search="true"
                                                                                                                                            name="centreCout">
                                                                                                                                            <option value="2907">PAYE M1 MAE EVREUX</option>
                                                                                                                                            <option value="2907">PAYE M1 MAE qsxsq</option>
                                                                                                                                            <option value="2907">PAYE M1 MAE ecezec</option>

                                                                                                                                        </select>-->
                                                                </td>
                                                                <td v-if="value.heuresDemandees != 0 ">
                                                                    <select :id="'centreCout-' + codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure"
                                                                            class="selectpicker"
                                                                            disabled

                                                                            name="">
                                                                        <option value="2907">PAYE M1 MAE EVREUX</option>
                                                                    </select></td>
                                                                <td>
                                                                    {{ heuresStatut(value) }}
                                                                </td>
                                                                <td style="font-size:12px;">

                                                                    <span
                                                                        v-if="value.heuresAPayer != value.heuresPayees &&  value.heuresAPayer == value.heuresDemandees">
                                                                        <button class="btn btn-danger"
                                                                                type="button" @click="this.supprimerDemandeMiseEnPaiement(value.mepId)">
                                                                            <i class="fa-solid fa-trash" style="color:white;"></i>
                                                                        </button>
                                                                    </span>
                                                                    <span
                                                                        v-if="value.heuresDemandees == 0">
                                                                        <button class="btn btn-primary" type="button"
                                                                                @click="this.ajouterDemandeMiseEnPaiement(codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure)">
                                                                                <!--<span style="color:white">OK</span>-->
                                                                            <i class="fa-solid fa-plus" style="color:white;"></i>

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
        },
        centreCoutSelect(datas)
        {
            let selectElement = document.createElement('select');
            let container = document.getElementById('test');
            for (var eotp in datas) {
                let optgroup = document.createElement('optgroup');
                optgroup.label = eotp;


                
                selectElement.appendChild(optgroup);

            }
            return eval(selectElement);
        },
        prepareCentresCouts(centresCouts, typeHeures)
        {

            let centresCoutesFiltered = [];
            for (var eotp in centresCouts) {
                let group = eotp;
                let child = [];
                centresCouts[eotp].forEach(function (centreCout, index) {
                    child.push(centreCout);
                })
                centresCoutesFiltered.push({[group]: child});
            }

            console.log(centresCoutesFiltered);

            return centresCoutesFiltered;
        }


    },
    mounted()
    {
        this.findServiceAPayer();


    },
    updated()
    {
        $('.selectpicker').selectpicker('render');
    }

}
</script>