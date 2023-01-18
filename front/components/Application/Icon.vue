<template>
    <i :class="`fas fa-${name}`"></i>
</template>

<script>
export default {
    name: "icon",
    props: {
        name: {required: true, type: String}
    },
}
</script>

<style scoped>

</style>