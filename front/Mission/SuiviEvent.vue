<template>
   {{ event.description }}
</template>

<script>
export default {
    name: "SuiviEvent",
    props: {
        event: {type: Object, required: true},
    },
    methods: {
        editEvent()
        {
            console.log(this.event);
        },
    }
}
</script>

<style scoped>

</style>