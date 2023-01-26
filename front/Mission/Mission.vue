<template>
    <div :id="mission.id" class="card bg-default">
        <form @submit.prevent="submitForm">
            <div class="card-header" :class="{'bg-info':mission.valide}">
                {{ mission.typeMission.libelle }}
                <span class="float-end">Du {{ mission.dateDebut }} au {{ mission.dateFin }}</span>
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
                                <div class="form-control">{{ mission.missionTauxRemu.libelle }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class=" form-label">Nombre d'heures prévisionnelles</label>
                                <div class="input-group mb-3">
                                    <div class="form-control">{{ heuresLib }}</div>
                                    <button onclick="alert('non implémenté')" class="input-group-btn btn btn-secondary">Suivi</button>
                                </div>

                                <!--<div class="form-control">{{ mission.heures }}</div>-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class=" form-label">Descriptif de la mission</label>
                                <div class="form-control">{{ mission.description }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <a v-if="mission.canEdit" :href="saisieUrl" class="btn btn-primary" @click.prevent="saisie">Modifier la mission</a>
                                <a class="btn btn-danger" @click.prevent="devalidation">Dévalidation de la mission</a>
                                <a class="btn btn-danger" :href="supprimerUrl" data-title="Suppression de la mission"
                                   data-content="Êtes-vous sur de vouloir supprimer la mission ?"
                                   data-confirm="true" @click.prevent="supprimer">Suppression de la mission</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Suivi -->
                        <div>
                            <label class=" form-label">Suivi</label>
                        </div>
                        <div>
                            <icon name="thumbs-up"/>
                            Créé le {{ mission.histoCreation }} par
                            <utilisateur :nom="mission.histoCreateur.displayName" :mail="mission.histoCreateur.email"/>
                        </div>
                        <div>
                            <icon :name="mission.valide ? 'thumbs-up' : 'thumbs-down'"/>
                            {{ validation }}
                            <utilisateur v-if="mission.validation && mission.validation.histoCreateur" :nom="mission.validation.histoCreateur.displayName"
                                         :mail="mission.validation.histoCreateur.email"/>
                        </div>
                        <div>
                            <icon :name="mission.contrat ? 'thumbs-up' : 'thumbs-down'"/>
                            {{ mission.contrat ? 'Contrat établi' : 'Pas de contrat' }}
                        </div>
                        <div>
                            Aucune heure réalisée
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
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
            mission: this.mission,
            saisieUrl: Util.url('mission/saisie/:mission', {mission: this.mission.id}),
            supprimerUrl: Util.url("mission/supprimer/:mission", {mission: this.mission.id}),
        };
    },
    computed: {
        heuresLib: function () {
            if (this.mission.heures === null || this.mission.heures === 0) {
                return 'Aucune heure saisie';
            } else if (this.mission.heures - this.mission.heuresValidees == 0) {
                return this.mission.heures + ' heures validés';
            } else if (this.mission.heuresValidees == 0) {
                return this.mission.heures + ' heures à valider';
            } else {
                return this.mission.heures + ' heures dont ' + (this.mission.heures - this.mission.heuresValidees) + ' à valider';
            }
        },
        validation: function () {
            if (this.mission.validation === null) {
                return 'A valider';
            } else if (this.mission.validation.id === null) {
                return 'Autovalidée';
            } else {
                return 'Validation du ' + this.mission.validation.histoCreation + ' par ';
            }
        }
    },
    methods: {
        saisie(event)
        {
            modAjax(event.target, (widget) => {
                axios.get(
                    Util.url("mission/get/:mission", {mission: this.mission.id})
                ).then(response => {
                    this.mission = response.data;
                });
            });
        },
        supprimer(event)
        {
            popAjax(event.target, (widget) => {
                this.$emit('supprimer', this.mission);
                alertFlash('Mission supprimée', 'success');
            });
        },
        valider()
        {

        },
        devalider()
        {

        },
        test()
        {

        },
    }
}
</script>

<style scoped>

</style>