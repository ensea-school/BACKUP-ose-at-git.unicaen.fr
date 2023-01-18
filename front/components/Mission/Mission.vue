<template>
    <div :id="mission.id" class="card bg-default">
        <form @submit.prevent="submitForm">
            <div class="card-header">
                {{ mission.typeMission.libelle }}
                <span class="badge bg-secondary">Du {{ mission.dateDebut }} au {{ mission.dateFin }}</span>
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
                                <div class="form-control">{{ mission.heures }}</div>
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
                                <input v-if="mission.canEdit" type="submit" class="btn btn-primary" value="Modifier la mission"/>
                                &nbsp;
                                <a class="btn btn-danger" @click="deleteMission">Suppression de la mission</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Suivi -->
                        <icon name="thumbs-up" /> Créé le {{ mission.histoCreation }} par <utilisateur :nom="mission.histoCreateur.displayName" :mail="mission.histoCreateur.email" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

import utilisateur from "@components/Application/Utilisateur.vue";
import icon from "@components/Application/Icon.vue";

export default {
    name: 'Mission',
    components: {
        utilisateur,
        icon
    },
    props: {
        mission: {required: true}
    },
    data()
    {
        return {
            mission: this.mission
        };
    },
    methods: {
        submitForm(event)
        {
            axios.post(
                Util.url('mission/modifier'),
                this.mission,
            ).then(response => {
                this.mission = response.data;
            });
        },
        deleteMission(mission)
        {
            this.$emit('delete', this.mission);
        }
    }
}
</script>

<style scoped>

</style>