<template>
    <br />
    <div v-if="countChoix() == 0">
        <div class="alert alert-info">Aucun service saisi</div>
    </div>
    <div v-if="countChoix()>1">
        <div class="d-flex justify-content-center align-items-center">
            <div>
                <table class="table table-borderless table-xs">
                    <tr v-for="(tvhLib, tvhId) in listeTypes" :key="tvhId">
                        <td v-for="(evhLib, evhId) in listeEtats" :key="evhId">
                            <div v-if="typesVolumesHoraires[tvhId].etats[evhId]">
                                <a :class="{ 'btn btn-outline-secondary btn-choixtevh': true, 'active': !(tvhId==typeVolumeHoraire && evhId==etatVolumeHoraire) }"
                                   @click="load(tvhId,evhId)">{{ tvhLib }} {{ evhLib }}</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div v-if="!typeVolumeHoraire" class="alert alert-info">Veuillez cliquer sur un des boutons ci-dessus pour
            afficher de détail de calcul des HETD
        </div>
    </div>
    <div v-else>
        <h2>{{ listeTypes[typeVolumeHoraire] }} {{ listeEtats[etatVolumeHoraire] }}</h2>
    </div>
    <div v-if="Object.keys(this.data).length > 0">
        <div v-if="data.typesHetd.length == 0">
            <div class="alert alert-warning">Aucune heure HETD n'a été calculée</div>
        </div>

        <h2>Paramètres intervenant</h2>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>Structure</th>
                <td v-if="data.intervenant.structure">{{ data.intervenant.structure.libelle }}</td>
                <td v-else><span class="text-secondary">Aucune structure d'affectation</span></td>
            </tr>
            <tr v-if="data.intervenant.heuresServiceStatutaire > 0 && data.intervenant.heuresServiceStatutaire != data.intervenant.serviceDu">
                <th>Heures de service statutaire</th>
                <td>
                    <u-heures :valeur="data.intervenant.heuresServiceStatutaire"/>
                </td>
            </tr>
            <tr v-if="data.intervenant.heuresServiceModifie > 0">
                <th>Heures de service modifié</th>
                <td>
                    <u-heures :valeur="data.intervenant.heuresServiceModifie"/>
                </td>
            </tr>
            <tr v-if="data.intervenant.serviceDu > 0">
                <th>Heures de service dû</th>
                <td>
                    <u-heures :valeur="data.intervenant.serviceDu"/>
                </td>
            </tr>
            <tr>
                <th>Dépassement de service dû sans HC</th>
                <td v-if="data.intervenant.depassementServiceDuSansHC">Oui</td>
                <td v-else>Non</td>
            </tr>
            <tr v-for="(plib, i) in data.iParams" :key="i">
                <th>{{ plib }} {{ i }}</th>
                <td>{{ data.intervenant.params[i] }}</td>
            </tr>
            </tbody>
        </table>

        <h2>Données utilisées pour le calcul des <abbr title="Heures équivalent TD">HETD</abbr></h2>

        <div v-if="data.intervenant.arrondisseur == 0" class="alert alert-info">L'arrondisseur de calcul HETD est
            désactivé
        </div>
        <div v-if="data.intervenant.arrondisseur == 1" class="alert alert-info">L'arrondisseur de calcul HETD est ici
            activé en mode "minimal" :
            les opérations d'arrondissage ne se font ici qu'au niveau des volumes horaires et ne prennent pas en compte
            la totalisation des heures au niveau de la fiche de l'intervenant.
        </div>

        <table class="table table-bordered table-xs table-details">
            <thead>
            </thead>
            <tbody>
            <template v-for="(sdata, sid) in data.services" :key="sid">
                <tr>
                    <th class="service" colspan="999">
                        <details-service-enseignement v-if="sdata.type=='enseignement'" :enseignement="sdata"/>
                        <details-service-referentiel v-else :referentiel="sdata"/>
                    </th>
                </tr>
                <tr class="details">
                    <th rowspan="2">&nbsp;</th>
                    <th colspan="2" v-if="data.visibilite.horaires">Horaire</th>
                    <th rowspan="2">Période</th>
                    <th rowspan="2" v-for="(param,pi) in data.vhParams" :key="pi">{{ param }}</th>
                    <th rowspan="2" v-if="data.visibilite.motifsNonPaiement">Motif non paiement</th>
                    <th rowspan="2">Type d'intervention</th>
                    <th rowspan="2" v-if="data.visibilite.servicesStatutaire"><abbr
                        title="Détermine si les heures peuvent être comptées dans le service statutaire de l'intervenant ou non">Peut
                        dans serv.</abbr></th>
                    <th colspan="2" v-if="data.visibilite.majorations">Majoration</th>
                    <th rowspan="2">Heures</th>
                    <th rowspan="2">&nbsp;</th>
                    <template v-for="(sousTypesHetd,typeHetd) in data.typesHetd" :key="typeHetd">
                        <th :rowspan="sousTypesHetd.length == 0 ? 2 : 1" :colspan="Math.max(sousTypesHetd.length,1)">
                            {{ typeHetd }}
                        </th>
                    </template>
                </tr>
                <tr class="details">
                    <th v-if="data.visibilite.horaires">Début</th>
                    <th v-if="data.visibilite.horaires">Fin</th>
                    <th v-if="data.visibilite.majorations">Service</th>
                    <th v-if="data.visibilite.majorations">Compl.</th>
                    <template v-for="typeHetd in data.typesHetd" :key="typeHetd">
                        <th v-for="(sth,k) in typeHetd" :key="k">{{ sth }}</th>
                    </template>
                </tr>
                <tr v-for="(vhdata, vhid) in sdata.volumesHoraires" :key="vhid">
                    <details-volume-horaire-enseignement v-if="sdata.type=='enseignement'" :vh="vhdata"
                                                         :visibilite="data.visibilite"/>
                    <details-volume-horaire-referentiel v-else :vhr="vhdata" :visibilite="data.visibilite"/>
                    <details-hetds :hetds="vhdata.hetd"/>
                </tr>
                <tr>
                    <th class="total" :colspan="totalColSpan()">Total</th>
                    <th>&nbsp;</th>
                    <details-hetds :hetds="sdata.hetd"/>
                </tr>
            </template>
            <tr>
                <th class="service" colspan="999">&nbsp;</th>
            </tr>
            <tr>
                <th class="total" :colspan="totalColSpan()">Total intervenant</th>
                <th>&nbsp;</th>
                <details-hetds :hetds="data.intervenant.hetd"/>
            </tr>
            </tbody>
        </table>

        <a v-if="canReporter" :href="reportUrl()" class="btn btn-secondary">Reporter les données de cet intervenant dans
            l'interface de tests de formule</a>
    </div>
</template>
<script>

import DetailsServiceEnseignement from "./DetailsServiceEnseignement.vue";
import DetailsServiceReferentiel from "./DetailsServiceReferentiel.vue";
import DetailsVolumeHoraireEnseignement from "./DetailsVolumeHoraireEnseignement.vue";
import DetailsVolumeHoraireReferentiel from "./DetailsVolumeHoraireReferentiel.vue";
import DetailsHetds from "./DetailsHetds.vue";

export default {
    name: 'Details',
    components: {
        DetailsVolumeHoraireReferentiel,
        DetailsVolumeHoraireEnseignement,
        DetailsServiceEnseignement,
        DetailsServiceReferentiel,
        DetailsHetds
    },
    props: {
        intervenant: {type: Number},
        typesVolumesHoraires: {type: Object},
        canReporter: {type: Boolean},
    },
    data()
    {
        return {
            data: {},
            typeVolumeHoraire: null,
            etatVolumeHoraire: null,
        };
    },
    mounted()
    {
        if (1 == this.countChoix()) {
            for (let type in this.typesVolumesHoraires) {
                this.typeVolumeHoraire = type;
                for (let etat in this.typesVolumesHoraires[type].etats) {
                    this.etatVolumeHoraire = etat;
                }
            }
            this.load(this.typeVolumeHoraire, this.etatVolumeHoraire);
        }
    },
    methods: {
        load(tvhId, evhId)
        {
            this.typeVolumeHoraire = tvhId;
            this.etatVolumeHoraire = evhId;

            const params = {
                intervenant: this.intervenant,
                typeVolumeHoraire: this.typeVolumeHoraire,
                etatVolumeHoraire: this.etatVolumeHoraire,
            };

            IntraNavigator.loadBegin();
            unicaenVue.axios.get(
                unicaenVue.url('intervenant/:intervenant/formule/details-data/:typeVolumeHoraire/:etatVolumeHoraire', params)
            ).then(response => {
                this.data = response.data;
                IntraNavigator.loadEnd();
            });
        },
        reportUrl()
        {
            const params = {
                intervenant: this.intervenant,
                typeVolumeHoraire: this.data.intervenant.typeVolumeHoraireId,
                etatVolumeHoraire: this.data.intervenant.etatVolumeHoraireId,
            };
            return unicaenVue.url('formule-test/creer-from-reel/:intervenant/:typeVolumeHoraire/:etatVolumeHoraire', params);
        },
        totalColSpan()
        {
            let tcs = 4;

            if (this.data.visibilite.horaires) {
                tcs += 2;
            }
            if (this.data.visibilite.motifsNonPaiement) {
                tcs += 1;
            }
            if (this.data.visibilite.servicesStatutaire) {
                tcs += 1;
            }
            if (this.data.visibilite.majorations) {
                tcs += 2;
            }

            return tcs;
        },
        countChoix()
        {
            let nb = 0;
            for (let type in this.typesVolumesHoraires) {
                for (let etat in this.typesVolumesHoraires[type].etats) {
                    nb++;
                }
            }
            return nb;
        },
    },
    computed: {
        listeTypes()
        {
            let types = {};
            for (let type in this.typesVolumesHoraires) {
                types[type] = this.typesVolumesHoraires[type].libelle;
            }
            return types;
        },
        listeEtats()
        {
            let etats = {};
            for (let type in this.typesVolumesHoraires) {
                for (let etat in this.typesVolumesHoraires[type].etats) {
                    etats[etat] = this.typesVolumesHoraires[type].etats[etat];
                }
            }

            const etatsTrie = Object.keys(etats) // Obtenir les clés
                                     .sort((a, b) => a - b) // Trier les clés numériquement
                                     .reduce((acc, key) => { // Reconstruire l'objet trié
                                         acc[key] = etats[key];
                                         return acc;
                                     }, {});

            return etatsTrie;
        },
    },
}

</script>
<style scoped>

table tr.details th {
    font-weight: bold;
    background-color: #f8f8f8;
}

.table-details tr.details th {
    font-size: 8pt;
}

.btn-choixtevh {
    width: 100%;
    margin-bottom: 6px;
}

.total {
    text-align: right;
    font-weight: bold;
}

table.table-details {
    border-top: 0px white solid;
}

th.service {

    border-left: 0px white solid;
    border-right: 0px white solid;
    padding-top: 1em;
    padding-bottom: 2px;
}

</style>