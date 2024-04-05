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
                    <td class="saisie"><select v-model="intervenant.formule" class="dinput" @change="updateFormule">
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
                    <td class="saisie"><select v-model="intervenant.structureCode" data-variable="intervenant" class="dinput"
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

                <tr v-for="i in [1,2,3,4,5]" :key="i" :class="'i-param i-param-'+i" v-show="formule['iParam'+i+'Libelle']">
                    <th class="i-param">{{ formule['iParam'+i+'Libelle'] }}</th>
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
            <div style="overflow:scroll">
                <table class="table table-bordered table-xs types-interventions">
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
            <table class="table table-xs table-bordered">
                <tr>
                    <th colspan="2">Service dû</th>
                    <td class="doutput" data-type="float" data-name="cServiceDu"></td>
                </tr>

                <tr>
                    <th rowspan="4">Service</th>
                    <th>FI</th>
                    <td class="doutput">{{ intervenant.heuresServiceFi }}</td>
                </tr>
                <tr>
                    <th>FA</th>
                    <td class="doutput">{{ intervenant.heuresServiceFa }}</td>
                </tr>
                <tr>
                    <th>FC</th>
                    <td class="doutput">{{ intervenant.heuresServiceFc }}</td>
                </tr>
                <tr>
                    <th>Référentiel</th>
                    <td class="doutput">{{ intervenant.heuresServiceReferentiel }}</td>
                </tr>
                <tr>
                    <th colspan="2">Total service dû assuré</th>
                    <td class="doutput">{{ intervenant.heuresService }}</td>
                </tr>
                <tr>
                    <th rowspan="5">Heures compl.</th>
                    <th>FI</th>
                    <td class="doutput">{{ intervenant.heuresComplFi }}</td>
                </tr>
                <tr>
                    <th>FA</th>
                    <td class="doutput">{{ intervenant.heuresComplFa }}</td>
                </tr>
                <tr>
                    <th>FC</th>
                    <td class="doutput">{{ intervenant.heuresComplFc }}</td>
                </tr>
                <tr>
                    <th>Référentiel</th>
                    <td class="doutput">{{ intervenant.heuresComplReferentiel }}</td>
                </tr>
                <tr>
                    <th colspan="2">Total heures compl. à payer</th>
                    <td class="doutput">{{ intervenant.heuresCompl }}</td>
                </tr>
                <tr>
                    <th>Primes</th>
                    <td class="doutput">{{ intervenant.heuresPrimes }}</td>
                </tr>
            </table>
            <!-- FIN résultats par intervenant -->

        </div>


        <h2>Heures effectuées (A saisir de manière chronologique)</h2>
        <table class="table table-bordered table-xs table-hover fvh">
            <thead>
            <tr>
                <th rowspan="3" style="width: 2em"></th>
                <th colspan="9" class="vh-donnees">Données</th>
                <th rowspan="3" style="width:2px">&nbsp;</th>
                <th colspan="9"><select id="affRes" class="form-control">
                    <option value="attendu">Résultats attendus (en HETD)</option>
                    <option value="hetd" selected="selected">Résultats calculés (en HETD)</option>
                    <option value="debug">Informations de débogage</option>
                </select></th>
            </tr>
            <tr>
                <th rowspan="2" style="width:8em">Structure</th>
                <th rowspan="2">Compte dans le service statutaire</th>
                <th rowspan="2">Type d'intervention</th>
                <th colspan="3">Répartition</th>
                <th colspan="2">Modulation</th>
                <th colspan="5" class="vh-params">Paramètres</th>
                <th rowspan="2">Heures</th>
                <th colspan="4" class="attendu">Service</th>
                <th colspan="5" class="attendu">Heures complémentaires</th>
                <th colspan="4" class="resultats">Service</th>
                <th colspan="5" class="resultats">Heures complémentaires</th>
                <th class="debug" rowspan="2" style="width:300px">Informations de débogage</th>
            </tr>
            <tr>
                <th>Fi</th>
                <th>Fa</th>
                <th>Fc</th>
                <th>Service dû</th>
                <th>Service compl.</th>
                <th v-for="i in [1,2,3,4,5]" :key="i" :class="`vh-param vh-param-`+i">Param. {{ i }}</th>
                <th class="resultats">Fi</th>
                <th class="resultats">Fa</th>
                <th class="resultats">Fc</th>
                <th class="resultats">Réfé-rentiel</th>
                <th class="resultats">Fi</th>
                <th class="resultats">Fa</th>
                <th class="resultats">Fc</th>
                <th class="resultats">Réfé-rentiel</th>
                <th class="resultats">Primes</th>

                <th class="attendu">Fi</th>
                <th class="attendu">Fa</th>
                <th class="attendu">Fc</th>
                <th class="attendu">Réfé-rentiel</th>
                <th class="attendu">Fi</th>
                <th class="attendu">Fa</th>
                <th class="attendu">Fc</th>
                <th class="attendu">Réfé-rentiel</th>
                <th class="attendu">Primes</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(vh, l) in volumesHoraires" :key="l">
                <th>{{ l + 1 }}</th>
                <td class="saisie"><select v-model="vh.structureCode" :data-variable="l" class="dinput" @change="selectStructure">
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
                    <u-input-float v-model="vh.tauxFi" is-pourc class="doutput" style="width:3em"/>
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
                <td v-for="p in [1,2,3,4,5]" :key="p" :class="`saisie vh-param vh-param-`+p"
                    style="width:5.7em"><input :v-model="vh['param'+p]" class="dinput"/>
                </td>

                <td class="saisie">
                    <u-input-float v-model="vh.heures" class="dinput"/>
                </td>
                <!--<td v-if="true" rowspan="16" style="width:2px">&nbsp;</td>-->
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesServiceFi" class="dinput"/>
                </td>
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesServiceFa" class="dinput"/>
                </td>
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesServiceFc" class="dinput"/>
                </td>
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesServiceReferentiel" class="dinput"/>
                </td>
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesComplFi" class="dinput"/>
                </td>
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesComplFa" class="dinput"/>
                </td>
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesComplFc" class="dinput"/>
                </td>
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesComplReferentiel" class="dinput"/>
                </td>
                <td class="attendu">
                    <u-input-float v-model="vh.heuresAttenduesPrimes" class="dinput"/>
                </td>

                <td class="attendu"><input v-model="vh.heuresServiceFi" class="dinput"/></td>
                <td class="attendu"><input v-model="vh.heuresServiceFa" class="dinput"/></td>
                <td class="attendu"><input v-model="vh.heuresServiceFc" class="dinput"/></td>
                <td class="attendu"><input v-model="vh.heuresServiceReferentiel" class="dinput"/></td>
                <td class="attendu"><input v-model="vh.heuresComplFi" class="dinput"/></td>
                <td class="attendu"><input v-model="vh.heuresComplFa" class="dinput"/></td>
                <td class="attendu"><input v-model="vh.heuresComplFc" class="dinput"/></td>
                <td class="attendu"><input v-model="vh.heuresComplReferentiel" class="dinput"/></td>
                <td class="attendu"><input v-model="vh.heuresPrimes" class="dinput"/></td>

                <td class="attendu">Debug</td>
            </tr>
            </tbody>
        </table>

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
    <button @click="charger">Charger</button>
</template>

<script>

export default {
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
    },
    computed: {
        filteredTypesIntervention()
        {
            let ti = Object.values(this.typesIntervention).filter(value => value);
            ti.unshift('');
            return ti;
        }
    },
    methods: {
        charger()
        {
            unicaenVue.axios.get(
                unicaenVue.url("formule-test/test-data/:id", {id: this.id})
            ).then(response => {
                if (!response.data.intervenant.tauxAutre1Code){
                    response.data.intervenant.tauxAutre1ServiceDu = undefined;
                    response.data.intervenant.tauxAutre1ServiceCompl = undefined;
                }
                if (!response.data.intervenant.tauxAutre2Code){
                    response.data.intervenant.tauxAutre2ServiceDu = undefined;
                    response.data.intervenant.tauxAutre2ServiceCompl = undefined;
                }
                if (!response.data.intervenant.tauxAutre3Code){
                    response.data.intervenant.tauxAutre3ServiceDu = undefined;
                    response.data.intervenant.tauxAutre3ServiceCompl = undefined;
                }
                if (!response.data.intervenant.tauxAutre4Code){
                    response.data.intervenant.tauxAutre4ServiceDu = undefined;
                    response.data.intervenant.tauxAutre4ServiceCompl = undefined;
                }
                if (!response.data.intervenant.tauxAutre5Code){
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
        updateFormule()
        {
            if (this.intervenant.formule) {
                this.formule = this.formules[this.intervenant.formule];
            }
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
                if ('intervenant' == variable){
                    this.intervenant.structureCode = structure;
                }else{
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

table.types-interventions * {
    border-width: 1px 1px;
}

table.types-interventions th {
    min-width: 5em;
    white-space: nowrap;
}

table.types-interventions td {
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
    width: 3em;
}

.fvh td .pourc {
    font-size: 8pt;
}

.dinput {
    border: none;
    height: 2em;
    width: 100%;
    background-color: #fff8dc;
}

.doutput {
    text-align: right;
}

input.doutput {
    border: none;
}

.debug.doutput {
    font-size: 8pt;
    width: 400px;
    white-space: unset;
}

.attendu {
    display: none;
}

.debug-info-table {
    overflow: auto;
}

.debug-table {
    font-size: 8pt;
}

.debug-table th {
    text-align: center;
    text-transform: uppercase;
    background-color: #ccc;
}

.debug-table td {
    text-align: right;
    min-width: 20px;
}

td .zero {
    color: gray;
}
</style>