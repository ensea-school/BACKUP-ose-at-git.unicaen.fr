<template>
    <p>{{ event.mission.libelle }}</p>
    <p>
        de {{ event.heureDebut }} à {{ event.heureFin }}, {{ event.heures }} heures
        <b-badge v-if="event.nocturne">Nocturne</b-badge>
        <b-badge v-if="event.formation">En formation</b-badge>
    </p>
    <p v-if="event.description">{{ event.description }}</p>
</template>

<script>
export default {
    name: "SuiviEvent",
    props: {
        event: {type: Object, required: true},
    },
}
</script>

<style scoped>

</style>