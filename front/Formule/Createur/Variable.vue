<template>

    <div class="description"><slot></slot></div>
    <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1">{{this.name}}</span>
        <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" :value="this.value">
    </div>

</template>
<script>

export default {
    name: 'Variable',
    props: {
        name: {type: String, required: true},
        value: {type: String, required: false},
        description: {type: String, required: true},
    }
}
</script>

<style scoped>

.description {
    margin-left: .5em;
    font-style: italic;
    color: gray;
}

</style>