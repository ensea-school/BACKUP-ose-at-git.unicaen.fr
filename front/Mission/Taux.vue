<template>
    <div class="card">
        {{ taux.code }}
    </div>
</template>

<script>
export default {
    name: "Taux",
    props: {
        taux: {required: true}
    },
}
</script>

<style scoped>

</style>