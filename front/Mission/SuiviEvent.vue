<template>
    <div class="event-content">
        <p>{{ event.mission.libelle }}</p>
        <p>
            de {{ event.heureDebut }} à {{ event.heureFin }}, {{ event.heures }} heures
            <b-badge v-if="event.nocturne">Nocturne</b-badge>
            <b-badge v-if="event.formation">En formation</b-badge>
        </p>
        <p v-if="event.description">{{ event.description }}</p>
    </div>
    <div class="event-actions">
        <div class="btn-group btn-group-sm">
            <button class="btn btn-light" @click="editEvent">
                <u-icon name="pen-to-square"/>
            </button>
            <button class="btn btn-light" @click="deleteEvent">
                <u-icon name="trash-can" class="text-danger"/>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "SuiviEvent",
    props: {
        event: {type: Object, required: true},
    },
    data(){
        return {
            suivi: this.$parent.$parent,
        };
    },
    methods: {

        editEvent(event)
        {
            this.suivi.editVolumeHoraire(this.event, event);
        },

        deleteEvent(event)
        {
            this.suivi.deleteVolumeHoraire(this.event, event);
        },

    },
}
</script>

<style scoped>

.event-content {
    flex-grow: 1;
}

.event-actions {
    align-self: flex-start;
}

</style>