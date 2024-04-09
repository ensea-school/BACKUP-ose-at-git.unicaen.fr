<template>
    <h1 class="page-header">Modification d'un test de formule</h1>
    <div class="row">
        <div class="col-md-6">

            <!-- DEBUT données intervenant -->
            <h2>Intervenant</h2>
            <table class="table table-xs table-bordered fti">
                <tr>
                    <th>Libellé</th>
                    <td class="saisie"><input v-model="intervenant.libelle" class="dinput"/></td>
                </tr>
                <tr>
                    <th>Formule</th>
                    <td class="saisie"><select v-model="intervenant.formule" class="dinput">
                        <option v-for="formule in formules" :value="formule.id">{{ formule.libelle }}</option>
                    </select></td>
                </tr>
                <tr>
                    <th>Année</th>
                    <td class="saisie"><select v-model="intervenant.annee" class="dinput">
                        <option v-for="annee in annees" :value="annee.id">{{ annee.libelle }}</option>
                    </select></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td class="saisie"><select v-model="intervenant.typeIntervenant" class="dinput">
                        <option v-for="typeIntervenant in typesIntervenants" :value="typeIntervenant.id">
                            {{ typeIntervenant.libelle }}
                        </option>
                    </select></td>
                </tr>
                <tr>
                    <th>Structure</th>
                    <td class="saisie"><select v-model="intervenant.structureCode" data-variable="intervenant"
                                               class="dinput"
                                               @change="selectStructure">
                        <option v-for="(v,k) in structures" :value="k" :key="k">{{ v }}</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <th>Type de volume horaire</th>
                    <td class="saisie"><select v-model="intervenant.typeVolumeHoraire" class="dinput">
                        <option v-for="typeVolumeHoraire in typesVolumesHoraires" :value="typeVolumeHoraire.id">
                            {{ typeVolumeHoraire.libelle }}
                        </option>
                    </select></td>
                </tr>
                <tr>
                    <th>État de volume horaire</th>
                    <td class="saisie"><select v-model="intervenant.etatVolumeHoraire" class="dinput">
                        <option v-for="etatVolumeHoraire in etatsVolumesHoraires" :value="etatVolumeHoraire.id">
                            {{ etatVolumeHoraire.libelle }}
                        </option>
                    </select></td>
                </tr>
                <tr>
                    <th>Heures de service statutaire</th>
                    <td class="saisie">
                        <u-input-float v-model="intervenant.heuresServiceStatutaire" class="dinput"/>
                    </td>
                </tr>
                <tr>
                    <th>Heures de service modifié</th>
                    <td class="saisie">
                        <u-input-float v-model="intervenant.heuresServiceModifie" class="dinput"/>
                    </td>
                </tr>
                <tr>
                    <th>Dépassement de service dû sans HC</th>
                    <td class="saisie"><select v-model="intervenant.depassementServiceDuSansHC" class="dinput">
                        <option :value="true">Oui</option>
                        <option :value="false">Non</option>
                    </select></td>
                </tr>

                <tr v-for="i in [1,2,3,4,5]" :key="i" :class="'i-param i-param-'+i"
                    v-show="formule['iParam'+i+'Libelle']">
                    <th class="i-param">{{ formule['iParam' + i + 'Libelle'] }}</th>
                    <td class="saisie"><input v-model="intervenant.param1" class="dinput"/></td>
                </tr>
            </table>
            <!-- FIN données intervenant -->


            <!-- DEBUT actions -->
            <button class="enregistrer btn btn-primary">Enregistrer et recalculer les HETD</button>
            <button class="exporter btn btn-secondary">Télécharger</button>
            <input type="file" id="importbtn" class="importer">
            <!-- FIN actions -->
        </div>

        <div class="col-md-5">

            <!-- DEBUT types d'intervention -->
            <h2>Types d'intervention (HETD)</h2>
            <div class="types-interventions">
                <table class="table table-bordered table-xs">
                    <tr>
                        <th></th>
                        <th colspan="3">Standards</th>
                        <th colspan="5">Personnalisés</th>
                    </tr>
                    <tr>
                        <th style="width:20%"></th>
                        <th style="width:5%">CM</th>
                        <th style="width:5%">TD</th>
                        <th style="width:5%">TP</th>
                        <th style="width:10%" v-show="tauxAutre1Visibility">
                            <input v-model="intervenant.tauxAutre1Code" class="dinput"/>
                        </th>
                        <th style="width:10%" v-show="tauxAutre2Visibility">
                            <input v-model="intervenant.tauxAutre2Code" class="dinput"/>
                        </th>
                        <th style="width:10%" v-show="tauxAutre3Visibility">
                            <input v-model="intervenant.tauxAutre3Code" class="dinput"/>
                        </th>
                        <th style="width:10%" v-show="tauxAutre4Visibility">
                            <input v-model="intervenant.tauxAutre4Code" class="dinput"/>
                        </th>
                        <th style="width:10%" v-show="tauxAutre5Visibility">
                            <input v-model="intervenant.tauxAutre5Code" class="dinput"/>
                        </th>
                    </tr>
                    <tr>
                        <th>En service</th>
                        <td class="saisie">
                            <u-input-float v-model="intervenant.tauxCmServiceDu" class="dinput"/>
                        </td>
                        <td>1</td>
                        <td class="saisie">
                            <u-input-float v-model="intervenant.tauxTpServiceDu" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre1Visibility">
                            <u-input-float v-model="intervenant.tauxAutre1ServiceDu" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre2Visibility">
                            <u-input-float v-model="intervenant.tauxAutre2ServiceDu" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre3Visibility">
                            <u-input-float v-model="intervenant.tauxAutre3ServiceDu" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre4Visibility">
                            <u-input-float v-model="intervenant.tauxAutre4ServiceDu" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre5Visibility">
                            <u-input-float v-model="intervenant.tauxAutre5ServiceDu" class="dinput"/>
                        </td>
                    </tr>
                    <tr>
                        <th>Au-delà du service</th>
                        <td class="saisie">
                            <u-input-float v-model="intervenant.tauxCmServiceCompl" class="dinput"/>
                        </td>
                        <td>1</td>
                        <td class="saisie">
                            <u-input-float v-model="intervenant.tauxTpServiceCompl" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre1Visibility">
                            <u-input-float v-model="intervenant.tauxAutre1ServiceCompl" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre2Visibility">
                            <u-input-float v-model="intervenant.tauxAutre2ServiceCompl" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre3Visibility">
                            <u-input-float v-model="intervenant.tauxAutre3ServiceCompl" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre4Visibility">
                            <u-input-float v-model="intervenant.tauxAutre4ServiceCompl" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre5Visibility">
                            <u-input-float v-model="intervenant.tauxAutre5ServiceCompl" class="dinput"/>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- FIN types d'intervention -->


            <!-- DEBUT résultats par intervenant -->
            <h2>Résultat</h2>
            <table class="table table-xs table-bordered resultats">
                <tr>
                    <th colspan="2">Service dû</th>
                    <td>
                        <u-heures :valeur="intervenant.serviceDu ?? NaN"/>
                    </td>
                </tr>

                <tr>
                    <th rowspan="4">Service</th>
                    <th>FI</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresServiceFi ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>FA</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresServiceFa ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>FC</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresServiceFc ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>Référentiel</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresServiceReferentiel ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">Total service dû assuré</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresService ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th rowspan="4">Heures compl.</th>
                    <th>FI</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresComplFi ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>FA</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresComplFa ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>FC</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresComplFc ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>Référentiel</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresComplReferentiel ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">Total heures compl. à payer</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresCompl ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">Primes</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresPrimes ?? NaN"/>
                    </td>
                </tr>
            </table>
            <!-- FIN résultats par intervenant -->

        </div>


        <!-- DEBUT saisie des volumes horaires -->
        <h2>Heures effectuées (A saisir de manière chronologique)</h2>
        <table class="table table-bordered table-xs table-hover fvh">
            <thead>
            <tr>
                <th rowspan="3"></th>
                <th :colspan="9+vhParamCount" class="vh-donnees">Données</th>
                <th rowspan="3" class="spacer">&nbsp;</th>
                <th colspan="9">
                    <select v-model="resMode" class="form-select res-mode">
                        <option value="attendu">Résultats attendus (en HETD)</option>
                        <option value="hetd" selected="selected">Résultats calculés (en HETD)</option>
                        <option value="debug">Informations de débogage</option>
                    </select>
                </th>
            </tr>
            <tr>
                <th rowspan="2" style="min-width:10em">Structure</th>
                <th rowspan="2">Compte dans le service statutaire</th>
                <th rowspan="2" style="min-width:5em">Type d'intervention</th>
                <th colspan="3">Répartition</th>
                <th colspan="2">Modulation</th>
                <th :colspan="vhParamCount" v-show="vhParamCount > 0">Paramètres</th>
                <th rowspan="2">Heures</th>

                <th colspan="4" v-show="resMode=='attendu'">Service</th>
                <th colspan="4" v-show="resMode=='attendu'">Heures compl.</th>
                <th rowspan="2" v-show="resMode=='attendu'">Primes</th>

                <th colspan="4" v-show="resMode=='hetd'">Service</th>
                <th colspan="4" v-show="resMode=='hetd'">Heures compl.</th>
                <th rowspan="2" v-show="resMode=='hetd'">Primes</th>

                <th rowspan="2" v-show="resMode=='debug'">Informations de débogage</th>
            </tr>
            <tr>
                <th>Fi</th>
                <th>Fa</th>
                <th>Fc</th>
                <th>Service dû</th>
                <th>Service compl.</th>
                <th v-for="i in [1,2,3,4,5]" :key="i" v-show="formule['vhParam'+i+'Libelle']">
                    {{ formule['vhParam' + i + 'Libelle'] }}
                </th>

                <th v-show="resMode=='attendu'">Fi</th>
                <th v-show="resMode=='attendu'">Fa</th>
                <th v-show="resMode=='attendu'">Fc</th>
                <th v-show="resMode=='attendu'">Réfé-rentiel</th>
                <th v-show="resMode=='attendu'">Fi</th>
                <th v-show="resMode=='attendu'">Fa</th>
                <th v-show="resMode=='attendu'">Fc</th>
                <th v-show="resMode=='attendu'">Réfé-rentiel</th>

                <th v-show="resMode=='hetd'">Fi</th>
                <th v-show="resMode=='hetd'">Fa</th>
                <th v-show="resMode=='hetd'">Fc</th>
                <th v-show="resMode=='hetd'">Réfé-rentiel</th>
                <th v-show="resMode=='hetd'">Fi</th>
                <th v-show="resMode=='hetd'">Fa</th>
                <th v-show="resMode=='hetd'">Fc</th>
                <th v-show="resMode=='hetd'">Réfé-rentiel</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(vh, l) in volumesHoraires" :key="l">
                <th>{{ l }}</th>
                <td class="saisie"><select v-model="vh.structureCode" :data-variable="l" class="dinput"
                                           @change="selectStructure">
                    <option v-for="(v,k) in structures" :value="k" :key="k">{{ v }}</option>
                </select></td>
                <td class="saisie"><select v-model="vh.serviceStatutaire" class="dinput">
                    <option :value="true">Oui</option>
                    <option :value="false">Non</option>
                </select></td>
                <td class="saisie"><select v-model="vh.typeIntervention" class="dinput">
                    <option v-for="ti in filteredTypesIntervention" :value="ti" :key="ti">
                        {{ ti }}
                    </option>
                </select></td>
                <td>
                    <u-input-float v-model="vh.tauxFi" is-pourc class="doutput" readonly style="width:3em"/>
                    <span class="pourc">%</span>
                </td>
                <td>
                    <u-input-float v-model="vh.tauxFa" is-pourc class="dinput" style="width:3em"/>
                    <span class="pourc">%</span>
                </td>
                <td>
                    <u-input-float v-model="vh.tauxFc" is-pourc class="dinput" style="width:3em"/>
                    <span class="pourc">%</span>
                </td>
                <td>
                    <u-input-float v-model="vh.ponderationServiceDu" is-pourc class="dinput" style="width:3em"/>
                    <span class="pourc">%</span>
                </td>
                <td>
                    <u-input-float v-model="vh.ponderationServiceCompl" is-pourc class="dinput" style="width:3em"/>
                    <span class="pourc">%</span>
                </td>
                <td v-show="formule.vhParam1Libelle"><input :v-model="vh.param1" class="dinput"/></td>
                <td v-show="formule.vhParam2Libelle"><input :v-model="vh.param2" class="dinput"/></td>
                <td v-show="formule.vhParam3Libelle"><input :v-model="vh.param3" class="dinput"/></td>
                <td v-show="formule.vhParam4Libelle"><input :v-model="vh.param4" class="dinput"/></td>
                <td v-show="formule.vhParam5Libelle"><input :v-model="vh.param5" class="dinput"/></td>

                <td class="saisie">
                    <u-input-float v-model="vh.heures" class="dinput"/>
                </td>

                <td class="spacer"><!-- espace --></td>

                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesServiceFi" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesServiceFa" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesServiceFc" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesServiceReferentiel" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesComplFi" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesComplFa" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesComplFc" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesComplReferentiel" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesPrimes" maximum-digits="2" class="dinput"/>
                </td>

                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresServiceFi" maximum-digits="2" readonly class="doutput"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresServiceFa" maximum-digits="2" readonly class="doutput"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresServiceFc" maximum-digits="2" readonly class="doutput"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresServiceReferentiel" maximum-digits="2" readonly class="doutput"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresComplFi" maximum-digits="2" readonly class="doutput"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresComplFa" maximum-digits="2" readonly class="doutput"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresComplFc" maximum-digits="2" readonly class="doutput"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresComplReferentiel" maximum-digits="2" readonly class="doutput"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresPrimes" maximum-digits="2" readonly class="doutput"/>
                </td>

                <td v-show="resMode=='debug'">Debug</td>
            </tr>
            </tbody>
        </table>
        <!-- FIN saisie des volumes horaires -->

        <!-- DEBUT Débogage général -->
        <div>
            <h2>Informations de débogage
                <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse"
                        data-bs-target="#debug-info"
                        aria-expanded="false" aria-controls="debug-info">
                    Afficher/masquer
                </button>
            </h2>
            <div class="collapse debug-info" id="debug-info"></div>
        </div>
        <!-- FIN Débogage général -->

        <a class="btn btn-secondary" :href="indexUrl"><i class="fas fa-rotate-left" aria-hidden="true"></i>
            Retour à la liste des formules</a>

    </div>
    <button @click="charger2">Charger2</button>
    <button @click="charger3">Charger3</button>
</template>

<script>

import UHeures from "../../Application/UI/UHeures.vue";

export default {
    components: {UHeures},
    props: {
        id: {type: Number},
        formules: {type: Object},
        annees: {type: Object},
        typesIntervenants: {type: Object},
        typesVolumesHoraires: {type: Object},
        etatsVolumesHoraires: {type: Object},
        defaultFormule: {type: Number},
    },
    data()
    {
        return {
            indexUrl: unicaenVue.url('formule-test'),
            dataUrl: unicaenVue.url('formule-test/data'),
            tauxAutre1Visibility: true,
            tauxAutre2Visibility: false,
            tauxAutre3Visibility: false,
            tauxAutre4Visibility: false,
            tauxAutre5Visibility: false,
            resMode: 'hetd',
            intervenant: {
                tauxCmServiceDu: 1.5,
                tauxCmServiceCompl: 1.5,
                tauxTpServiceDu: 1,
                tauxTpServiceCompl: 0.6666666666667,
            },
            volumesHoraires: [],
            formule: {
                iParam1Libelle: undefined,
                iParam2Libelle: undefined,
                iParam3Libelle: undefined,
                iParam4Libelle: undefined,
                iParam5Libelle: undefined,
                vhParam1Libelle: undefined,
                vhParam2Libelle: undefined,
                vhParam3Libelle: undefined,
                vhParam4Libelle: undefined,
                vhParam5Libelle: undefined,
            },

            structures: {},
            typesIntervention: [
                'CM',
                'TD',
                'TP',
                undefined,
                undefined,
                undefined,
                undefined,
                undefined,
                'Référentiel'
            ],
        };
    },
    watch: {
        'intervenant.tauxAutre1Code'(code)
        {
            this.tauxAutre2Visibility = code || this.intervenant.tauxAutre1Code || this.intervenant.tauxAutre2ServiceDu || this.intervenant.tauxAutre2ServiceCompl;
            this.typesIntervention[3] = code;
        },
        'intervenant.tauxAutre2Code'(code)
        {
            this.tauxAutre3Visibility = code || this.intervenant.tauxAutre2Code || this.intervenant.tauxAutre3ServiceDu || this.intervenant.tauxAutre3ServiceCompl;
            this.typesIntervention[4] = code;
        },
        'intervenant.tauxAutre3Code'(code)
        {
            this.tauxAutre4Visibility = code || this.intervenant.tauxAutre3Code || this.intervenant.tauxAutre4ServiceDu || this.intervenant.tauxAutre4ServiceCompl;
            this.typesIntervention[5] = code;
        },
        'intervenant.tauxAutre4Code'(code)
        {
            this.tauxAutre5Visibility = code || this.intervenant.tauxAutre4Code || this.intervenant.tauxAutre5ServiceDu || this.intervenant.tauxAutre5ServiceCompl;
            this.typesIntervention[6] = code;
        },
        'intervenant.tauxAutre5Code'(code)
        {
            this.typesIntervention[7] = code;
        },
        'intervenant.formule'(id)
        {
            if (id) {
                this.formule = this.formules[id];
            }
        },
    },
    computed: {
        filteredTypesIntervention()
        {
            let ti = Object.values(this.typesIntervention).filter(value => value);
            ti.unshift('');
            return ti;
        },
        vhParamCount()
        {
            let count = 0;
            for (let i = 1; i < 6; i++) {
                if (this.formule['vhParam' + i + 'Libelle']) {
                    count++;
                }
            }

            return count;
        }
    },
    methods: {
        charger2()
        {
            this.id2 = 10029;
            this.charger();
        },
        charger3()
        {
            this.id2 = 10031;
            this.charger();
        },
        charger()
        {
            unicaenVue.axios.get(
                unicaenVue.url("formule-test/test-data/:id", {id: this.id2})
            ).then(response => {
                if (!response.data.intervenant.tauxAutre1Code) {
                    response.data.intervenant.tauxAutre1ServiceDu = undefined;
                    response.data.intervenant.tauxAutre1ServiceCompl = undefined;
                }
                if (!response.data.intervenant.tauxAutre2Code) {
                    response.data.intervenant.tauxAutre2ServiceDu = undefined;
                    response.data.intervenant.tauxAutre2ServiceCompl = undefined;
                }
                if (!response.data.intervenant.tauxAutre3Code) {
                    response.data.intervenant.tauxAutre3ServiceDu = undefined;
                    response.data.intervenant.tauxAutre3ServiceCompl = undefined;
                }
                if (!response.data.intervenant.tauxAutre4Code) {
                    response.data.intervenant.tauxAutre4ServiceDu = undefined;
                    response.data.intervenant.tauxAutre4ServiceCompl = undefined;
                }
                if (!response.data.intervenant.tauxAutre5Code) {
                    response.data.intervenant.tauxAutre5ServiceDu = undefined;
                    response.data.intervenant.tauxAutre5ServiceCompl = undefined;
                }
                this.intervenant = response.data.intervenant;
                this.volumesHoraires = response.data.volumesHoraires;
                this.updateStructures();
            });
        },
        updateStructures()
        {
            let structures = {'': ''};

            if (this.intervenant.structureCode) {
                structures[this.intervenant.structureCode] = this.intervenant.structureCode;
            }
            for (let vh in this.volumesHoraires) {
                const vhStructure = this.volumesHoraires[vh].structureCode;
                if (vhStructure) {
                    structures[vhStructure] = vhStructure;
                }
            }
            structures['__UNIV__'] = 'Université (établissement)';
            structures['__EXTERIEUR__'] = 'Extérieur (autre établissement)';
            structures['__new_structure__'] = '- Ajout d\'une nouvelle structure -';
            this.structures = structures;
        },
        selectStructure(event)
        {
            if ('__new_structure__' == event.target.value) {
                this.addStructure(event.target);
            }
        },
        addStructure(element)
        {
            const structure = prompt("Ajout d'une nouvelle structure");

            this.structures[structure] = structure;

            setTimeout(() => {
                const variable = element.dataset.variable;
                if ('intervenant' == variable) {
                    this.intervenant.structureCode = structure;
                } else {
                    this.volumesHoraires[variable].structureCode = structure;
                }
            }, 200);
        },
    },
    mounted()
    {
        this.updateStructures();
    },
}

</script>
<style scoped>

.types-interventions {
    overflow: scroll;
}

.types-interventions table * {
    border-width: 1px 1px;
}

.types-interventions table th {
    min-width: 5em;
    white-space: nowrap;
}

.types-interventions table td {
    min-width: 5em;
    white-space: nowrap;
}

.saisie {
    background-color: #fff8dc;
}

.fvh th {
    font-size: 8pt;
}

.fvh td {
    white-space: nowrap;
    padding: 1px !important;
    min-width: 4em;
}

.fvh td .pourc {
    font-size: 8pt;
}

.spacer {
    max-width: 5px !important;
    min-width: 5px !important;
}

.dinput {
    border: none;
    height: 2em;
    width: 100%;
    background-color: #fff8dc;
}

.doutput {
    border: none;
    height: 2em;
    width: 100%;
    background-color: transparent;
}

.res-mode {
    width: 100%;
    min-width: 300px;
}

.resultats td {
    text-align: right;
}

</style>