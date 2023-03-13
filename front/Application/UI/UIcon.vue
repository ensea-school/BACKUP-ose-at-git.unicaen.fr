<template>
    <i :class="`fas fa-${name} text-${variant}`"></i>
</template>

<script>
export default {
    name: "UIcon",
    props: {
        name: {required: true, type: String},
        variant: {required: false, type: String},
    },
}
</script>

<style scoped>

</style>