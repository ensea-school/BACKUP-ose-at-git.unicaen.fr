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

                <tr class="i-param i-param-1"
                    v-show="formule['iParam1Libelle']">
                    <th class="i-param">{{ formule['iParam1Libelle'] }}</th>
                    <td class="saisie"><input v-model="intervenant.param1" class="dinput"/></td>
                </tr>
                <tr class="i-param i-param-2"
                    v-show="formule['iParam2Libelle']">
                    <th class="i-param">{{ formule['iParam2Libelle'] }}</th>
                    <td class="saisie"><input v-model="intervenant.param2" class="dinput"/></td>
                </tr>
                <tr class="i-param i-param-3"
                    v-show="formule['iParam3Libelle']">
                    <th class="i-param">{{ formule['iParam3Libelle'] }}</th>
                    <td class="saisie"><input v-model="intervenant.param3" class="dinput"/></td>
                </tr>
                <tr class="i-param i-param-4"
                    v-show="formule['iParam4Libelle']">
                    <th class="i-param">{{ formule['iParam4Libelle'] }}</th>
                    <td class="saisie"><input v-model="intervenant.param4" class="dinput"/></td>
                </tr>
                <tr class="i-param i-param-5"
                    v-show="formule['iParam5Libelle']">
                    <th class="i-param">{{ formule['iParam5Libelle'] }}</th>
                    <td class="saisie"><input v-model="intervenant.param5" class="dinput"/></td>
                </tr>
            </table>
            <!-- FIN données intervenant -->


            <!-- DEBUT actions -->
            <div class="actions">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <b-button variant="primary" @click="enregistrer">Enregistrer les données</b-button>
                    </div>
                    <div class="col-md-6">
                        <b-button variant="secondary" @click="calculer">Calculer les HETD</b-button>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <button class="exporter btn btn-secondary" @click="exporter">Télécharger les données</button>
                    </div>
                    <div class="col-md-6">
                        <label for="importbtn">
                            <span class="btn btn-secondary">Téléverser un jeu de données</span>
                            <input type="file" id="importbtn" class="importer" @change="importer">
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button class="exporter btn btn-secondary" @click="exporterCsv">Télécharger les données en CSV
                        </button>
                    </div>
                </div>
            </div>

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
                            <u-input-float v-model="intervenant.tauxCmServiceDu" :fraction="true" class="dinput"/>
                        </td>
                        <td>1</td>
                        <td class="saisie">
                            <u-input-float v-model="intervenant.tauxTpServiceDu" :fraction="true" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre1Visibility">
                            <u-input-float v-model="intervenant.tauxAutre1ServiceDu" :fraction="true" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre2Visibility">
                            <u-input-float v-model="intervenant.tauxAutre2ServiceDu" :fraction="true" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre3Visibility">
                            <u-input-float v-model="intervenant.tauxAutre3ServiceDu" :fraction="true" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre4Visibility">
                            <u-input-float v-model="intervenant.tauxAutre4ServiceDu" :fraction="true" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre5Visibility">
                            <u-input-float v-model="intervenant.tauxAutre5ServiceDu" :fraction="true" class="dinput"/>
                        </td>
                    </tr>
                    <tr>
                        <th>Au-delà du service</th>
                        <td class="saisie">
                            <u-input-float v-model="intervenant.tauxCmServiceCompl" :fraction="true" class="dinput"/>
                        </td>
                        <td>1</td>
                        <td class="saisie">
                            <u-input-float v-model="intervenant.tauxTpServiceCompl" :fraction="true" class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre1Visibility">
                            <u-input-float v-model="intervenant.tauxAutre1ServiceCompl" :fraction="true"
                                           class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre2Visibility">
                            <u-input-float v-model="intervenant.tauxAutre2ServiceCompl" :fraction="true"
                                           class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre3Visibility">
                            <u-input-float v-model="intervenant.tauxAutre3ServiceCompl" :fraction="true"
                                           class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre4Visibility">
                            <u-input-float v-model="intervenant.tauxAutre4ServiceCompl" :fraction="true"
                                           class="dinput"/>
                        </td>
                        <td class="saisie" v-show="tauxAutre5Visibility">
                            <u-input-float v-model="intervenant.tauxAutre5ServiceCompl" :fraction="true"
                                           class="dinput"/>
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
                <tr>
                    <th rowspan="4">Heures non payables</th>
                    <th>FI</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresNonPayableFi ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>FA</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresNonPayableFa ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>FC</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresNonPayableFc ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th>Référentiel</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresNonPayableReferentiel ?? NaN"/>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">Total heures non payables</th>
                    <td>
                        <u-heures :valeur="intervenant.heuresNonPayable ?? NaN"/>
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
                <th :colspan="10+vhParamCount" class="vh-donnees">Données</th>
                <th rowspan="3" class="spacer">&nbsp;</th>
                <th colspan="13">
                    <select v-model="resMode" class="form-select res-mode">
                        <option value="attendu">Résultats attendus (en HETD)</option>
                        <option value="hetd" selected="selected">Résultats calculés (en HETD)</option>
                        <option value="debug">Informations de débogage</option>
                    </select>
                </th>
            </tr>
            <tr>
                <th rowspan="2" style="min-width:10em">Structure</th>
                <th rowspan="2">Non payable</th>
                <th rowspan="2">Compte dans le service statutaire</th>
                <th rowspan="2" style="min-width:5em">Type d'intervention</th>
                <th colspan="3">Répartition</th>
                <th colspan="2">Modulation</th>
                <th :colspan="vhParamCount" v-show="vhParamCount > 0">Paramètres</th>
                <th rowspan="2">Heures</th>

                <th colspan="4" v-show="resMode=='attendu'">Service</th>
                <th colspan="4" v-show="resMode=='attendu'">Heures compl.</th>
                <th rowspan="2" v-show="resMode=='attendu'">Primes</th>
                <th colspan="4" v-show="resMode=='attendu'">Heures non payables</th>

                <th colspan="4" v-show="resMode=='hetd'">Service</th>
                <th colspan="4" v-show="resMode=='hetd'">Heures compl.</th>
                <th rowspan="2" v-show="resMode=='hetd'">Primes</th>
                <th colspan="4" v-show="resMode=='hetd'">Heures non payables</th>

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
                <th v-show="resMode=='attendu'">Référentiel</th>
                <th v-show="resMode=='attendu'">Fi</th>
                <th v-show="resMode=='attendu'">Fa</th>
                <th v-show="resMode=='attendu'">Fc</th>
                <th v-show="resMode=='attendu'">Référentiel</th>
                <th v-show="resMode=='attendu'">Fi</th>
                <th v-show="resMode=='attendu'">Fa</th>
                <th v-show="resMode=='attendu'">Fc</th>
                <th v-show="resMode=='attendu'">Référentiel</th>

                <th v-show="resMode=='hetd'">Fi</th>
                <th v-show="resMode=='hetd'">Fa</th>
                <th v-show="resMode=='hetd'">Fc</th>
                <th v-show="resMode=='hetd'">Référentiel</th>
                <th v-show="resMode=='hetd'">Fi</th>
                <th v-show="resMode=='hetd'">Fa</th>
                <th v-show="resMode=='hetd'">Fc</th>
                <th v-show="resMode=='hetd'">Référentiel</th>
                <th v-show="resMode=='hetd'">Fi</th>
                <th v-show="resMode=='hetd'">Fa</th>
                <th v-show="resMode=='hetd'">Fc</th>
                <th v-show="resMode=='hetd'">Référentiel</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(vh, l) in volumesHoraires" :key="l">
                <th>{{ l + 1 }}</th>
                <td><select v-model="vh.structureCode" :data-variable="l" class="dinput"
                            @change="selectStructure">
                    <option v-for="(v,k) in structures" :value="k" :key="k">{{ v }}</option>
                </select></td>
                <td><select v-model="vh.nonPayable" class="dinput" v-show="vh.structureCode">
                    <option :value="true">Oui</option>
                    <option :value="false">Non</option>
                </select></td>
                <td><select v-model="vh.serviceStatutaire" class="dinput" v-show="vh.structureCode">
                    <option :value="true">Oui</option>
                    <option :value="false">Non</option>
                </select></td>
                <td><select v-model="vh.typeInterventionCode" class="dinput" v-show="vh.structureCode">
                    <option v-for="ti in filteredTypesIntervention" :value="ti" :key="ti">
                        {{ ti }}
                    </option>
                </select></td>
                <td>
                    <div v-show="!['', null, 'Référentiel'].includes(vh.typeInterventionCode)">
                        <u-input-float v-model="vh.tauxFi" is-pourc class="doutput" readonly style="width:3em"/>
                        <span class="pourc">%</span>
                    </div>
                </td>
                <td>
                    <div v-show="!['', null, 'Référentiel'].includes(vh.typeInterventionCode)">
                        <u-input-float v-model="vh.tauxFa" is-pourc class="dinput" :data-variable="l"
                                       @change="majTauxFi" @click="majTauxFi" style="width:3em"/>
                        <span class="pourc">%</span>
                    </div>
                </td>
                <td>
                    <div v-show="!['', null, 'Référentiel'].includes(vh.typeInterventionCode)">
                        <u-input-float v-model="vh.tauxFc" is-pourc class="dinput" :data-variable="l"
                                       @change="majTauxFi" @click="majTauxFi" style="width:3em"/>
                        <span class="pourc">%</span>
                    </div>
                </td>
                <td>
                    <div v-show="!['', null, 'Référentiel'].includes(vh.typeInterventionCode)">
                        <u-input-float v-model="vh.ponderationServiceDu" is-pourc class="dinput" style="width:3em"/>
                        <span class="pourc"
                        >%</span>
                    </div>

                </td>
                <td>
                    <div v-show="!['', null, 'Référentiel'].includes(vh.typeInterventionCode)">
                        <u-input-float v-model="vh.ponderationServiceCompl" is-pourc class="dinput" style="width:3em"/>
                        <span class="pourc">%</span>
                    </div>
                </td>
                <td v-show="formule.vhParam1Libelle"><input v-model="vh.param1" class="dinput"
                                                            v-show="vh.structureCode"/></td>
                <td v-show="formule.vhParam2Libelle"><input v-model="vh.param2" class="dinput"
                                                            v-show="vh.structureCode"/></td>
                <td v-show="formule.vhParam3Libelle"><input v-model="vh.param3" class="dinput"
                                                            v-show="vh.structureCode"/></td>
                <td v-show="formule.vhParam4Libelle"><input v-model="vh.param4" class="dinput"
                                                            v-show="vh.structureCode"/></td>
                <td v-show="formule.vhParam5Libelle"><input v-model="vh.param5" class="dinput"
                                                            v-show="vh.structureCode"/></td>

                <td>
                    <u-input-float v-model="vh.heures" v-show="!['', null].includes(vh.typeInterventionCode)"
                                   class="dinput" @change="calculer"/>
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
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesNonPayableFi" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesNonPayableFa" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesNonPayableFc" maximum-digits="2" class="dinput"/>
                </td>
                <td v-show="resMode=='attendu'">
                    <u-input-float v-model="vh.heuresAttenduesNonPayableReferentiel" maximum-digits="2" class="dinput"/>
                </td>

                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresServiceFi" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesServiceFi != null && Math.round(vh.heuresAttenduesServiceFi*100) != Math.round(vh.heuresServiceFi*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresServiceFa" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesServiceFa != null && Math.round(vh.heuresAttenduesServiceFa*100) != Math.round(vh.heuresServiceFa*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresServiceFc" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesServiceFc != null && Math.round(vh.heuresAttenduesServiceFc*100) != Math.round(vh.heuresServiceFc*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresServiceReferentiel" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesServiceReferentiel != null && Math.round(vh.heuresAttenduesServiceReferentiel*100) != Math.round(vh.heuresServiceReferentiel*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresComplFi" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesComplFi != null && Math.round(vh.heuresAttenduesComplFi*100) != Math.round(vh.heuresComplFi*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresComplFa" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesComplFa != null && Math.round(vh.heuresAttenduesComplFa*100) != Math.round(vh.heuresComplFa*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresComplFc" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesComplFc != null && Math.round(vh.heuresAttenduesComplFc*100) != Math.round(vh.heuresComplFc*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresComplReferentiel" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesComplReferentiel != null && Math.round(vh.heuresAttenduesComplReferentiel*100) != Math.round(vh.heuresComplReferentiel*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresPrimes" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesPrimes != null && Math.round(vh.heuresAttenduesPrimes*100) != Math.round(vh.heuresPrimes*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresNonPayableFi" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesNonPayableFi != null && Math.round(vh.heuresAttenduesNonPayableFi*100) != Math.round(vh.heuresNonPayableFi*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresNonPayableFa" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesNonPayableFa != null && Math.round(vh.heuresAttenduesNonPayableFa*100) != Math.round(vh.heuresNonPayableFa*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresNonPayableFc" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesNonPayableFc != null && Math.round(vh.heuresAttenduesNonPayableFc*100) != Math.round(vh.heuresNonPayableFc*100)}"/>
                </td>
                <td v-show="resMode=='hetd'">
                    <u-input-float v-model="vh.heuresNonPayableReferentiel" maximum-digits="2" tabindex="-1" readonly
                                   :class="{doutput: true, 'bg-danger': vh.heuresAttenduesNonPayableReferentiel != null && Math.round(vh.heuresAttenduesNonPayableReferentiel*100) != Math.round(vh.heuresNonPayableReferentiel*100)}"/>
                </td>

                <td v-show="resMode=='debug'" class="debug-td">
                    <div v-if="debug.vh && debug.vh[l]">
                    <span v-for="(val,cell) in debug.vh[l]" class="debug-cell">
                        {{ cell }} <span class="debug-val">{{ Math.round(val * 100) / 100 }}</span>
                    </span>&nbsp;
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- FIN saisie des volumes horaires -->

        <!-- DEBUT Débogage général -->
        <div v-if="resMode=='debug' && debug.global">
            <h4>Débogage : calculs globaux</h4>
            <span v-for="(val,cell) in debug.global" class="debug-cell">
                {{ cell }} <span class="debug-val">{{ Math.round(val * 100) / 100 }}</span>
            </span>&nbsp;
        </div>
        <!-- FIN Débogage général -->

        <div>
            <a class="btn btn-secondary" :href="indexUrl"><i class="fas fa-rotate-left" aria-hidden="true"></i>
                Retour à la liste des formules</a>
        </div>
    </div>
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
            resMode: 'hetd',
            debug: {},
            intervenant: {
                formule: undefined,
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
        charger()
        {
            unicaenVue.axios.get(
                unicaenVue.url("formule-test/saisir-data/:id", {id: this.id ? this.id : this.intervenant.id ? this.intervenant.id : 0})
            ).then(response => {
                this.intervenant = this.dropTauxNonUtilises(response.data.intervenant);
                this.volumesHoraires = response.data.volumesHoraires;
                this.debug = {};
                this.addVolumeHoraire();
                this.updateStructures();
            });
        },
        enregistrer()
        {
            unicaenVue.axios.post(
                unicaenVue.url("formule-test/enregistrer/:id", {id: this.id ? this.id : this.intervenant.id ? this.intervenant.id : 0}),
                {
                    intervenant: this.intervenant,
                    volumesHoraires: this.volumesHoraires,
                }
            ).then(response => {
                this.intervenant = this.dropTauxNonUtilises(response.data.intervenant);
                this.volumesHoraires = response.data.volumesHoraires;
                if (response.data.debug) {
                    this.debug = response.data.debug;
                } else {
                    this.debug = {};
                }
                this.addVolumeHoraire();
                this.updateStructures();
            });
        },
        calculer()
        {
            unicaenVue.axios.post(
                unicaenVue.url("formule-test/enregistrer/:id", {id: this.id ? this.id : this.intervenant.id ? this.intervenant.id : 0}),
                {
                    intervenant: this.intervenant,
                    volumesHoraires: this.volumesHoraires,
                    simpleCalcul: true
                }
            ).then(response => {
                this.intervenant = this.dropTauxNonUtilises(response.data.intervenant);
                this.volumesHoraires = response.data.volumesHoraires;
                if (response.data.debug) {
                    this.debug = response.data.debug;
                } else {
                    this.debug = {};
                }
                this.addVolumeHoraire();
                this.updateStructures();
            });
        },
        exporter: function () {
            const content = {
                intervenant: this.intervenant,
                volumesHoraires: this.volumesHoraires,
            };
            const filename = this.intervenant.libelle;

            var a = document.createElement('a');
            var blob = new Blob([JSON.stringify(content)], {'type': 'text/json'});
            a.href = window.URL.createObjectURL(blob);
            a.download = 'Test de formule ' + filename + '.json';
            a.click();
        },


        exporterCsv: function () {
            let content = "Structure;Heures non payables;" +
                "Compte dans le service statutaire;Type d’intervention;Taux FI;Taux FA;Taux FC;" +
                "Modulation service dû;Modulation service compl.;" +
                "Param 1;Param 2;Param 3;Param 4;Param 5;Heures;" +
                "Service FI;Service FA;Service FC;Service Référentiel;" +
                "HC FI;HC FA;HC FC;HC Référentiel;Primes;" +
                "Non payable FI;Non payable FA;Non payable FC;Non payable Référentiel\n";

            const array = [1, 2, 3, 4, 5];

            for (const l in this.volumesHoraires) {
                const vh = this.volumesHoraires[l];
                content += (vh.structureCode ? vh.structureCode : '') + ";"
                    + (vh.nonPayable ? 'Oui' : 'Non') + ";"
                    + (vh.serviceStatutaire ? 'Oui' : 'Non') + ";"
                    + (vh.referentiel ? 'Référentiel' : (vh.typeInterventionCode ? vh.typeInterventionCode : '')) + ";"
                    + (vh.tauxFi ? vh.tauxFi.toLocaleString('fr-FR') : '') + ";"
                    + (vh.tauxFa ? vh.tauxFa.toLocaleString('fr-FR') : '') + ";"
                    + (vh.tauxFc ? vh.tauxFc.toLocaleString('fr-FR') : '') + ";"
                    + (vh.ponderationServiceDu ? vh.ponderationServiceDu.toLocaleString('fr-FR') : '') + ";"
                    + (vh.ponderationServiceCompl ? vh.ponderationServiceCompl.toLocaleString('fr-FR') : '') + ";"
                    + (vh.param1 ? vh.param1 : '') + ";"
                    + (vh.param2 ? vh.param2 : '') + ";"
                    + (vh.param3 ? vh.param3 : '') + ";"
                    + (vh.param4 ? vh.param4 : '') + ";"
                    + (vh.param5 ? vh.param5 : '') + ";"
                    + (vh.heures ? vh.heures.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresServiceFi ? vh.heuresServiceFi.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresServiceFa ? vh.heuresServiceFa.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresServiceFc ? vh.heuresServiceFc.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresServiceReferentiel ? vh.heuresServiceReferentiel.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresComplFi ? vh.heuresComplFi.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresComplFa ? vh.heuresComplFa.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresComplFc ? vh.heuresComplFc.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresComplReferentiel ? vh.heuresComplReferentiel.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresPrimes ? vh.heuresPrimes.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresNonPayableFi ? vh.heuresNonPayableFi.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresNonPayableFa ? vh.heuresNonPayableFa.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresNonPayableFc ? vh.heuresNonPayableFc.toLocaleString('fr-FR') : '') + ";"
                    + (vh.heuresNonPayableReferentiel ? vh.heuresNonPayableReferentiel.toLocaleString('fr-FR') : '') + ";"
                    + "\n";
            }

            const filename = this.intervenant.libelle;

            var a = document.createElement('a');
            var blob = new Blob([content], {'type': 'text/csv'});
            a.href = window.URL.createObjectURL(blob);
            a.download = 'Test de formule ' + filename + '.csv';
            a.click();
        },


        importer: function (event) {
            const file = event.target.files[0];

            // Vérifiez si le fichier est de type JSON
            if (!file.type.match('application/json')) {
                console.error("Le fichier n'est pas de type JSON.");
                return;
            }

            const reader = new FileReader();

            // Lorsque la lecture du fichier est terminée
            reader.onload = (e) => {
                try {
                    const jsonContent = JSON.parse(e.target.result);

                    jsonContent.intervenant.id = this.intervenant.id;

                    this.intervenant = jsonContent.intervenant;
                    this.volumesHoraires = jsonContent.volumesHoraires;
                    this.updateStructures();
                } catch (error) {
                    console.error("Erreur lors de l'analyse du contenu JSON :", error);
                }
            };

            // Lire le contenu du fichier en tant que texte
            reader.readAsText(file);
        },

        dropTauxNonUtilises(data)
        {
            if (!data.tauxAutre1Code) {
                data.tauxAutre1ServiceDu = undefined;
                data.tauxAutre1ServiceCompl = undefined;
            }
            if (!data.tauxAutre2Code) {
                data.tauxAutre2ServiceDu = undefined;
                data.tauxAutre2ServiceCompl = undefined;
            }
            if (!data.tauxAutre3Code) {
                data.tauxAutre3ServiceDu = undefined;
                data.tauxAutre3ServiceCompl = undefined;
            }
            if (!data.tauxAutre4Code) {
                data.tauxAutre4ServiceDu = undefined;
                data.tauxAutre4ServiceCompl = undefined;
            }
            if (!data.tauxAutre5Code) {
                data.tauxAutre5ServiceDu = undefined;
                data.tauxAutre5ServiceCompl = undefined;
            }

            return data;
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
            if (event.target.dataset.variable != 'intervenant') {
                // si le select de structure n'est pas celui de l'intervenant, mais d'un volume horaire
                const vhi = parseInt(event.target.dataset.variable);

                if (vhi == this.lastVolumeHoraireIndex()) {
                    // Si on est sur la dernière ligne
                    if (this.volumesHoraires[vhi].structureCode) {
                        // et que la structure du dernier n'est pas vide
                        // alors, on ajoute un nouvel item pour pouvoir faire la saisie
                        this.addVolumeHoraire();
                    }
                }
            }
        },
        majTauxFi(event)
        {
            const vhi = event.target.dataset.variable;
            this.volumesHoraires[vhi].tauxFi = 1 - this.volumesHoraires[vhi].tauxFa - this.volumesHoraires[vhi].tauxFc;
        },
        lastVolumeHoraireIndex()
        {
            if (this.volumesHoraires.length == 0) {
                return -1;
            } else {
                return parseInt(Object.keys(this.volumesHoraires).pop());
            }
        },
        addVolumeHoraire()
        {
            this.volumesHoraires.push({
                structureCode: null,
                typeInterventionCode: null,
                tauxFi: 1,
                tauxFa: 0,
                tauxFc: 0,
                ponderationServiceDu: 1,
                ponderationServiceCompl: 1,
                serviceStatutaire: true,
                heures: null,
            });
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
        this.charger();

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
    min-width: 4em;
    padding: 0px;
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


.debug-td {
    min-width: 42em !important;
}

.debug-cell {
    background-color: #ccc;
    color: black;
    margin: 2px;
    padding: 3px;
    border-radius: 5px;
    font-size: 8pt;
    white-space: nowrap;
    float: left;
}

.debug-val {
    background-color: white;
    padding: 3px;
    padding-top: 0px;
    padding-bottom: 0px;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    color: black;
    font-size: 8pt;
}

.importer {
    display: none;
}

.actions .btn {
    width: 100%;
}

.actions label {
    display: block;
}

</style>