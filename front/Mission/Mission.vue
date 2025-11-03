<template>
    <div :id="mission.id" :class="{'bg-success':mission.valide,'bg-default':!mission.valide}" class="card">
        <form @submit.prevent="submitForm">
            <div class="card-header card-header-h3">
                <h5 v-if="mission.libelleMission">
                    {{ mission.libelleMission }}
                    <span class="float-end">Du <u-date :value="mission.dateDebut"/> au <u-date
                        :value="mission.dateFin"/></span>
                </h5>
                <h6 v-if="mission.libelleMission">{{ mission.typeMission.libelle }}</h6>
                <h5 v-if="!mission.libelleMission">{{ mission.typeMission.libelle }}
                    <span class="float-end">Du <u-date :value="mission.dateDebut"/> au <u-date
                        :value="mission.dateFin"/></span></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Propriétés -->
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Composante en charge du suivi</label>
                                <div class="form-control">{{ mission.structure.libelle }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class=" form-label">Taux de rémunération</label>
                                <div class="form-control">{{ mission.tauxRemu ? mission.tauxRemu.libelle : null }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class=" form-label">Taux majoré (dimanches/jours fériés)</label>
                                <div class="form-control">
                                    {{
                                        mission.tauxRemuMajore ? mission.tauxRemuMajore.libelle : mission.tauxRemu ? 'Idem ('+mission.tauxRemu.libelle+')' : null
                                    }}
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label class=" form-label">Nombre d'heures effectives prévisionnelles</label>
                                <div class="input-group mb-3">
                                    <div class="form-control" v-html="heuresLib"></div>
                                    <button :data-bs-target="`#details-${mission.id}`" class="input-group-btn btn btn-secondary"
                                            data-bs-toggle="modal">
                                        Détails
                                    </button>
                                </div>
                            </div>
                            <div v-if="mission.typeMission.besoinFormation" class="col-md-5">
                                <label class=" form-label">Heures de formation prévues</label>
                                <div class="form-control">{{ mission.heuresFormation }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class=" form-label">Descriptif de la mission</label>
                                <div class="form-control">{{ mission.description }}</div>
                            </div>
                        </div>
                        <div v-if="mission.typeMission.accompagnementEtudiants" class="row">
                            <div class="col-md-12">
                                <label class=" form-label">Etudiants suivis</label>
                                <div class="form-control">
                                    <pre>{{ mission.etudiantsSuivis }}</pre>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">&nbsp;</div>
                        </div>
                        <div class="row">
                            <!-- Boutons d'actions -->
                            <div class="col-md-12">
                                <a v-if="mission.canSaisie"
                                   :href="saisieUrl"
                                   class="btn btn-primary"
                                   @click.prevent="saisie">Modifier</a>

                                <a v-if="mission.canValider"
                                   :href="validerUrl"
                                   class="btn btn-secondary"
                                   data-content="Êtes-vous sur de vouloir valider la mission ?"
                                   data-title="Validation de la mission"
                                   @click.prevent="valider">Valider</a>

                                <a v-if="mission.canDevalider"
                                   :href="devaliderUrl"
                                   class="btn btn-danger"
                                   data-content="Êtes-vous sur de vouloir dévalider la mission ?"
                                   data-title="Dévalidation de la mission"
                                   @click.prevent="devalider">Dévalider</a>

                                <a v-if="mission.canSupprimer"
                                   :href="supprimerUrl"
                                   class="btn btn-danger"
                                   data-content="Êtes-vous sur de vouloir supprimer la mission ?"
                                   data-title="Suppression de la mission"
                                   @click.prevent="supprimer">Supprimer</a>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Suivi -->
                        <div>
                            <label class=" form-label">Suivi</label>
                        </div>
                        <div>
                            <u-icon name="thumbs-up" variant="success"/>
                            Créé le
                            <u-date :value="mission.histoCreation"/>
                            par
                            <utilisateur :mail="mission.histoCreateur.email" :nom="mission.histoCreateur.displayName"/>
                        </div>
                        <div>
                            <u-icon :name="mission.valide ? 'thumbs-up' : 'thumbs-down'"
                                    :variant="mission.valide ? 'success' : 'info'"/>
                            {{ validationText }}
                            <utilisateur v-if="mission.validation && mission.validation.histoCreateur"
                                         :mail="mission.validation.histoCreateur.email"
                                         :nom="mission.validation.histoCreateur.displayName"/>
                        </div>
                        <div>
                            <u-icon :name="mission.contrat ? 'thumbs-up' : 'thumbs-down'"
                                    :variant="mission.contrat ? 'success' : 'info'"/>
                            {{ mission.contrat ? 'Contrat établi' : 'Pas de contrat' }}
                        </div>
                        <div>
                            {{ mission.heuresRealisees }} heure{{ mission.heuresRealisees < 2 ? '' : 's' }}
                            réalisée{{ mission.heuresRealisees < 2 ? '' : 's' }}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <u-modal :id="`details-${mission.id}`" title="Détail des heures prévisionnelles">
        <template #body>
            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                    <th>Heures</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="vh in mission.volumesHorairesPrevus" :key="vh.id">
                    <td style="text-align: right">
                        <u-heures :valeur="vh.heures"/>
                    </td>
                    <td>
                        <u-icon name="thumbs-up" variant="success"/>
                        Saisi par
                        <utilisateur :mail="vh.histoCreateur.email"
                                     :nom="vh.histoCreateur.displayName"/>
                        le
                        <u-date :value="vh.histoCreation"/>
                        <br/>
                        <u-icon :name="vh.valide ? 'thumbs-up' : 'thumbs-down'"
                                :variant="vh.valide ? 'success' : 'info'"/>
                        {{
                            vh.validation && vh.validation.id == null ? 'Autovalidé' : (!vh.validation ? 'à valider' : '')
                        }}
                        <span v-if="vh.validation && vh.validation.histoCreateur">
                            Validé par <utilisateur :mail="vh.validation.histoCreateur.email"
                                                    :nom="vh.validation.histoCreateur.displayName"/> le <u-date
                            :value="vh.validation.histoCreation"/>
                        </span>
                    </td>
                    <td>
                        <a v-if="vh.canValider"
                           :data-id="vh.id"
                           class="btn btn-secondary"
                           data-content="Êtes-vous sur de vouloir valider ce volume horaire ?"
                           data-title="Validation du volume horaire"
                           @click.prevent="volumeHoraireValider">Valider</a>

                        <a v-if="vh.canDevalider"
                           :data-id="vh.id"
                           class="btn btn-danger"
                           data-content="Êtes-vous sur de vouloir dévalider ce volume horaire ?"
                           data-title="Dévalidation du volume horaire"
                           @click.prevent="volumeHoraireDevalider">Dévalider</a>

                        <a v-if="vh.canSupprimer"
                           :data-id="vh.id"
                           class="btn btn-danger"
                           data-content="Êtes-vous sur de vouloir supprimer le volume horaire ?"
                           data-title="Suppression du volume horaire"
                           @click.prevent="volumeHoraireSupprimer">Supprimer</a>


                    </td>
                </tr>
                </tbody>
            </table>
        </template>
        <template #footer>

        </template>
    </u-modal>
    <u-confirm-dialog ref="confirmDialog"/>

</template>

<script>


export default {
    name: 'Mission',
    props: {
        mission: {required: true}
    },

    data()
    {
        return {
            boxOne: '',
            boxTwo: '',
            validationText: this.calcValidation(this.mission.validation),

            saisieUrl: unicaenVue.url('mission/saisie/:mission', {mission: this.mission.id}),
            validerUrl: unicaenVue.url('mission/valider/:mission', {mission: this.mission.id}),
            devaliderUrl: unicaenVue.url('mission/devalider/:mission', {mission: this.mission.id}),
            supprimerUrl: unicaenVue.url("mission/supprimer/:mission", {mission: this.mission.id}),
        };
    },
    watch: {
        'mission.validation'(validation)
        {
            this.validationText = this.calcValidation(validation);
        }
    },
    computed: {
        heuresLib: function () {
            if (this.mission.heures === null || this.mission.heures === 0) {
                return 'Aucune heure saisie';
            } else if (this.mission.heures == this.mission.heuresValidees) {
                return Util.formattedHeures(this.mission.heures)+' heures (validées)';
            } else if (this.mission.heuresValidees == 0) {
                return Util.formattedHeures(this.mission.heures)+' heures (non validées)';
            } else {
                return '<span class="bg-info">'+Util.formattedHeures(this.mission.heures)+'</span> heures ('+Util.formattedHeures(this.mission.heuresValidees)+' validées)';
            }
        }
    },
    methods: {
        calcValidation(validation)
        {
            if (validation === null) {
                return 'A valider';
            } else if (validation.id === null) {
                return 'Autovalidée';
            } else {
                return 'Validation du '+Util.dateToString(validation.histoCreation)+' par ';
            }
        },
        saisie(event)
        {
            modAjax(event.currentTarget, (widget) => {
                this.refresh();
            });
        },
        async supprimer(event)
        {
            const url = event.currentTarget.href;
            const confirmed = await this.$refs.confirmDialog.open(
                "Voulez-vous vraiment supprimer cette mission ?",
                "Confirmer la suppression"
            );
            if (!url) {
                console.error("Aucune URL trouvée sur le bouton !");
                return;
            }
            if (confirmed) {
                const response = await unicaenVue.axios.get(url);
                if (response && response.data) {
                    this.$emit('refresh', response.data);
                }
            }

        },

        async valider(event)
        {
            const url = event.currentTarget.href;

            if (!url) {
                console.error("Aucune URL trouvée sur le bouton !");
                return;
            }
            const confirmed = await this.$refs.confirmDialog.open(
                "Voulez-vous vraiment valider cette mission ?",
                "Confirmer la validation"
            );

            if (confirmed) {
                const response = await unicaenVue.axios.get(url);
                if (response && response.data) {
                    this.$emit('refresh', response.data);
                }
            }


        },
        async devalider(event)
        {
            const url = event.currentTarget.href;
            if (!url) {
                console.error("Aucune URL trouvée sur le bouton !");
                return;
            }
            const confirmed = await this.$refs.confirmDialog.open(
                "Voulez-vous vraiment dévalider cette mission ?",
                "Confirmer la dévalidation"
            );

            if (confirmed) {
                const response = await unicaenVue.axios.get(url);
                if (response && response.data) {
                    this.$emit('refresh', response.data);
                }
            }
        },
        async volumeHoraireSupprimer(event)
        {
            const url = unicaenVue.url('mission/volume-horaire/supprimer/:missionVolumeHoraire', {missionVolumeHoraire: event.currentTarget.dataset.id});
            if (!url) {
                console.error("Aucune URL trouvée sur le bouton !");
                return;
            }
            const confirmed = await this.$refs.confirmDialog.open(
                "Voulez-vous vraiment valider cette mission ?",
                "Confirmer la validation"
            );

            if (confirmed) {
                const response = await unicaenVue.axios.get(url);
                if (response && response.data) {
                    this.$emit('refresh', response.data);
                }
            }
        },
        async volumeHoraireValider(event)
        {
            const url = unicaenVue.url('mission/volume-horaire/valider/:missionVolumeHoraire', {missionVolumeHoraire: event.currentTarget.dataset.id});
            if (!url) {
                console.error("Aucune URL trouvée sur le bouton !");
                return;
            }
            const confirmed = await this.$refs.confirmDialog.open(
                "Voulez-vous vraiment valider les volumes horaires ?",
                "Confirmer la validation"
            );

            if (confirmed) {
                const response = await unicaenVue.axios.get(url);
                if (response && response.data) {
                    this.$emit('refresh', response.data);
                }
            }
        },
        async volumeHoraireDevalider(event)
        {
            const url = unicaenVue.url('mission/volume-horaire/devalider/:missionVolumeHoraire', {missionVolumeHoraire: event.currentTarget.dataset.id});
            if (!url) {
                console.error("Aucune URL trouvée sur le bouton !");
                return;
            }
            const confirmed = await this.$refs.confirmDialog.open(
                "Voulez-vous vraiment dévalider cette mission ?",
                "Confirmer la validation"
            );

            if (confirmed) {
                const response = await unicaenVue.axios.get(url);
                if (response && response.data) {
                    this.$emit('refresh', response.data);
                }
            }
        },
        refresh()
        {
            unicaenVue.axios.get(
                unicaenVue.url("mission/get/:mission", {mission: this.mission.id})
            ).then(response => {
                this.$emit('refresh', response.data);
            });
        }
    }
}
</script>

<style scoped>
.card-header h5 {
    font-weight: 500;
}

.btn {
    margin-left: 2px;
    margin-right: 2px;
}
</style>