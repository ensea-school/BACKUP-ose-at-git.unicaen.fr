<template>
    {{ event.texte }}
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