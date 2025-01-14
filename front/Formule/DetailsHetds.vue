<template>
    <details-hetd v-for="(hetd,id) in hetds" :key="id" :type="id" :hetd="hetd"></details-hetd>
</template>
<script>

import DetailsHetd from "./DetailsHetd.vue";

export default {
    name: 'DetailsHetds',
    components: {
        DetailsHetd
    },
    props: {
        hetds: {type: Object},
    },
}

</script>
<style scoped>

</style>