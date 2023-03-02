<template>
    {{ event.texte }}
    <button class="btn btn-light"><u-icon name="pen-to-square" /></button>
    <button class="btn btn-light"><u-icon name="trash-can" /></button>
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