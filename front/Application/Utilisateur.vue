<template>
    <a :href="`mailto:${mail}`">{{ nom }}</a>
</template>

<script>
export default {
    name: "Utilisateur",
    props: {
        nom: String,
        mail: String
    }
}
</script>

<style scoped>

</style>