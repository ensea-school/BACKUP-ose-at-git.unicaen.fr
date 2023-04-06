<template>
    Nombre = {{ nombre}}<br/>
    chaine = {{ chaine }}
</template>

<script>
export default {
    name: "VmTest",
    props: {
        'nombre': {required: true, type: Number},
        'chaine': {required: true, type: String},
    },
}
</script>

<style scoped>

</style>