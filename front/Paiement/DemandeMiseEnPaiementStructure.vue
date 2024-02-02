<template>
    <div :id="'demande-mise-en-paiement-' + datas.code" class="accordion-item">
        <h2 id="dmep-vacataires-heading" class="accordion-header">
            <button aria-controls="dmep-vacataires-collapse" aria-expanded="true" class="accordion-button" data-bs-target="#dmep-vacataires-collapse"
                    data-bs-toggle="collapse"
                    type="button">
                {{ datas.code + ' - ' + datas.libelle }}
            </button>

        </h2>
        <div id="dmep-vacataires-collapse" aria-labelledby="dmep-vacataires-heading" class="accordion-collapse collapse show">
            <div class="accordion-body">
                <div v-for="(etape, codeEtape) in datas.etapes">
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
                                                        <th scope="col" style="width:20%;font-size:12px;">Heures</th>
                                                        <th scope="col" style="width:40%;font-size:12px;">Centre cout</th>
                                                        <th scope="col" style="width:25%;font-size:12px;">Statut</th>
                                                        <th style="width:15%;font-size:12px;">Action</th>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(value,id) in typeHeure.heures" class="detailHeure">
                                                            <td v-if="value.heuresDemandees != 0 " style="width:20%;">{{ value.heuresAPayer }} hetd</td>
                                                            <td v-if="value.heuresDemandees == 0 " style="width:20%;">
                                                                <div class="input-group col-1">
                                                                    <input
                                                                        :id="'heures-' + codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure"
                                                                        :data-domaine-fonctionnel-id="value.domaineFonctionnelId"
                                                                        :data-formule-res-service-id="value.formuleResServiceId"
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
                                                                    <option value="">Aucun centre de cout</option>
                                                                    <optgroup
                                                                        v-for="group in filtrerCentresCouts(datas.centreCoutPaiement,value.typeHeureCode)"
                                                                        :key="group.group"
                                                                        :label="group.group">
                                                                        <option v-for="item in group.child" :key="item.value" :value="item.centreCoutId">
                                                                            {{ item.centreCoutCode + ' - ' + item.centreCoutLibelle }}
                                                                        </option>

                                                                    </optgroup>
                                                                </select>
                                                            </td>
                                                            <td v-if="value.heuresDemandees != 0 ">
                                                                {{ value.centreCout.code + ' - ' + value.centreCout.libelle }}
                                                            </td>
                                                            <td v-html="heuresStatutToString(value)">

                                                            </td>
                                                            <td style="font-size:12px;">
                                                                <span
                                                                    v-if="value.heuresAPayer != value.heuresPayees &&  value.heuresAPayer == value.heuresDemandees">
                                                                    <button :id="'remove-' + value.mepId"
                                                                            class="btn btn-danger"
                                                                            type="button" @click="this.supprimerDemandeMiseEnPaiement(value.mepId)">
                                                                        <i class="fa-solid fa-trash" style="color:white;"></i>
                                                                    </button>
                                                                </span>
                                                                <span
                                                                    v-if="value.heuresDemandees == 0">
                                                                    <button :id="'add-' + codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure"
                                                                            class="btn btn-primary"
                                                                            type="button"
                                                                            @click="this.ajouterDemandeMiseEnPaiement(codeEtape + '-' + codeEnseignement + '-' + codeTypeHeure)">
                                                                            <u-icon name="plus"/>
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
                <div class="cartridge gray bordered" style="padding-bottom: 5px">
                    <span>Référentiel</span>
                    <!--                        <span>{{ fonction.libelle }}</span>-->
                </div>
                <div v-for="(fonction, codeFonction) in datas.fonctionsReferentiels">

                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <table class="table mt-3 table-bordered">
                                    <thead class="table-light">
                                    <tr>
                                        <th colspan="2">{{ fonction.libelle }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <table class="table table-sm ">
                                                <thead>
                                                <th scope="col" style="width:20%;font-size:12px;">Heures</th>
                                                <th scope="col" style="width:40%;font-size:12px;">Centre cout</th>
                                                <th scope="col" style="width:25%;font-size:12px;">Statut</th>
                                                <th style="width:15%;font-size:12px;">Action</th>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(value,id) in fonction.heures" class="detailHeure">
                                                    <td v-if="value.heuresDemandees != 0 " style="width:20%;">{{ value.heuresAPayer }} hetd</td>
                                                    <td v-if="value.heuresDemandees == 0 " style="width:20%;">
                                                        <div class="input-group col-1">
                                                            <input
                                                                :id="'heures-' + codeFonction"
                                                                :data-domaine-fonctionnel-id="value.domaineFonctionnelId"
                                                                :data-formule-res-service-ref-id="value.formuleResServiceRefId"
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
                                                    <!--<td>{{ value.centreCout.libelle }}</td>-->
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
                                                                <option v-for="item in group.child" :key="item.value" :value="item.centreCoutId">
                                                                    {{ item.centreCoutCode + ' - ' + item.centreCoutLibelle }}
                                                                </option>

                                                            </optgroup>
                                                        </select>
                                                    </td>
                                                    <td v-if="value.heuresDemandees != 0 ">
                                                        {{ value.centreCout.code + ' - ' + value.centreCout.libelle }}
                                                    </td>
                                                    <td v-html="heuresStatutToString(value)">

                                                    </td>
                                                    <td style="font-size:12px;">
                                                                <span
                                                                    v-if="value.heuresAPayer != value.heuresPayees &&  value.heuresAPayer == value.heuresDemandees">
                                                                    <button :id="'remove-' + value.mepId"
                                                                            class="btn btn-danger"
                                                                            type="button" @click="this.supprimerDemandeMiseEnPaiement(value.mepId)">
                                                                        <i class="fa-solid fa-trash" style="color:white;"></i>
                                                                    </button>
                                                                </span>
                                                        <span
                                                            v-if="value.heuresDemandees == 0">
                                                                    <button :id="'add-' + codeFonction"
                                                                            class="btn btn-primary"
                                                                            type="button"
                                                                            @click="this.ajouterDemandeMiseEnPaiement(codeFonction)">
                                                                            <u-icon name="plus"/>
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
            </div>
            <div style="text-align:center;margin-bottom:20px;">
                <button :id="'add-all-' + datas.code"
                        class="btn btn-primary"
                        type="button"
                        @click="demanderToutesLesHeuresEnPaiement(datas.code)">
                    TOUT METTRE EN PAIEMENT POUR CETTE COMPOSANTE
                </button>
            </div>
        </div>
    </div>

</template>

<script>

export default {

    name: "DemandeMiseEnPaiementStructure",
    props: {
        datas: {type: Array, required: true},
        intervenant: {required: true},
    },
    data()
    {
        return {
            urlListeServiceAPayer: unicaenVue.url('intervenant/:intervenant/mise-en-paiement/liste-service-a-payer', {intervenant: this.intervenant}),
        }
    },
    computed:
        {},
    methods: {
        heuresStatutToString(value)
        {
            if (value.heuresAPayer == value.heuresPayees) {
                return '<span style="font-size:12px;line-height:20px;" class="badge bg-success">Paiement effectué</span>';
            }
            if (value.heuresAPayer == value.heuresDemandees) {
                return '<span style="font-size:12px;line-height:20px;" class="badge bg-secondary text-dark">Paiement en cours</span>';
            }
            if (value.heuresDemandees == 0) {
                return '<span style="font-size:12px;line-height:20px;" class="badge bg-light text-dark">A payer</span>';
            }
            return 'indetermine';
        },
        supprimerDemandeMiseEnPaiement(id)
        {
            //On récupere le bouton d'ajout
            let btnRemove = document.getElementById('remove-' + id);
            //On desactive le bouton pour éviter le multi click
            btnRemove.disabled = true;
            unicaenVue.axios.get(unicaenVue.url('paiement/:intervenant/supprimer-demande/:dmep', {intervenant: this.intervenant, dmep: id}))
                .then(response => {
                    this.$emit('refresh');
                    setTimeout(() => {
                        btnRemove.disabled = false;
                    }, 1500);
                })
                .catch(error => {
                    console.error(error);
                })
        },
        ajouterDemandeMiseEnPaiement(id)
        {

            //On récupere le bouton d'ajout
            let btnAdd = document.getElementById('add-' + id);
            //On desactive le bouton pour éviter le multi click
            btnAdd.disabled = true;
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

            unicaenVue.axios.post(unicaenVue.url('paiement/:intervenant/ajouter-demande', {intervenant: this.intervenant}), datas)
                .then(response => {
                    this.$emit('refresh');
                    setTimeout(() => {
                        btnAdd.disabled = false;
                    }, 1500);
                })
                .catch(error => {
                    console.error(error);
                })

        },
        demanderToutesLesHeuresEnPaiement(codeStructure)
        {
            //On récupere le bouton d'ajout
            let btnAddAll = document.getElementById('add-all-' + codeStructure);
            //On desactive le bouton pour éviter le multi click
            btnAddAll.disabled = true;
            btnAddAll.innerText = "VEUILLEZ PATIENTER..."
            let datas = [];
            let parent = document.getElementById("demande-mise-en-paiement-" + codeStructure);
            let demandesMiseEnPaiement = parent.getElementsByTagName("tr");
            for (var i = 0; i < demandesMiseEnPaiement.length; i++) {
                //Si j'ai un champs input dans une des lignes de tableau j'ai une demande de mise en paiement à faire
                if (demandesMiseEnPaiement[i].getElementsByTagName('input').length > 0 && demandesMiseEnPaiement[i].classList.contains('detailHeure')) {
                    let inputHeure = demandesMiseEnPaiement[i].getElementsByTagName('input')[0];
                    //Si le select de centre de cout à une valeur alors je peux faire la demande de mise en paiement
                    if (demandesMiseEnPaiement[i].getElementsByTagName('select')[0].value != '') {

                        let selectCentreCout = demandesMiseEnPaiement[i].getElementsByTagName('select')[0];
                        let heureADemander = inputHeure.value;
                        let centreCoutId = selectCentreCout.value;
                        let typeHeureId = (inputHeure.hasAttribute('data-type-heures-id') ? inputHeure.getAttribute('data-type-heures-id') : '');
                        let formuleResServiceId = (inputHeure.hasAttribute('data-formule-res-service-id') ? inputHeure.getAttribute('data-formule-res-service-id') : '');
                        let formuleResServiceRefId = (inputHeure.hasAttribute('data-formule-res-service-ref-id') ? inputHeure.getAttribute('data-formule-res-service-ref-id') : '');
                        let missionId = (inputHeure.hasAttribute('data-mission-id') ? inputHeure.getAttribute('data-mission-id') : '');

                        let demande = {
                            heures: heureADemander,
                            centreCoutId: centreCoutId,
                            typeHeuresId: typeHeureId,
                            formuleResServiceId: formuleResServiceId,
                            formuleResServiceRefId: formuleResServiceRefId,
                            missionId: missionId,
                        }
                        datas.push(demande);
                    }

                }

            }
            unicaenVue.axios.post(unicaenVue.url('paiement/:intervenant/all-demande', {intervenant: this.intervenant}), datas)
                .then(response => {
                    this.$emit('refresh');
                    setTimeout(() => {
                        btnAddAll.disabled = false;
                        btnAddAll.innerText = "TOUT METTRE EN PAIEMENT POUR CETTE COMPOSANTE"
                    }, 1500);

                })
                .catch(error => {
                    console.log(error)
                })
        },
        filtrerCentresCouts(centresCouts, typeHeures)
        {
            /*
            * Méthode permettant de filtrer les centres coûts disponibles par rapport
            * aux types d'heures à payer (fi, fa, fc etc...)
            * */
            console.log(typeHeures);


            let centresCoutesFiltered = [];
            for (var eotp in centresCouts) {
                let group = eotp;
                let child = [];
                centresCouts[eotp].forEach(function (centreCout, index) {
                    if (centreCout[typeHeures] == 1) {
                        console.log(centreCout);
                        child.push(centreCout);
                    }
                })
                if (child.length != 0) {
                    centresCoutesFiltered.push({group: group, child});
                }
            }


            return centresCoutesFiltered;
        },
        totalHeure(heures)
        {
            let total = 0;
            for (var heure in heures) {
                total += Number(heures[heure].heuresAPayer);
            }
            return total;
        }


    },


}

</script>