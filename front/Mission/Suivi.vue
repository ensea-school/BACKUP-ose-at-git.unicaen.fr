<template>
    <u-calendar date="2023-02-01"></u-calendar>
    <button @click="test()">Test</button>
</template>

<script>

export default {
    name: 'Suivi',
    methods: {
        test()
        {
            console.log(this.lignes());
        }
    }
}
</script>

<style scoped>

</style>