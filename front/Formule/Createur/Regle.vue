<template>
    <div class="form-group row">
        <label for="regle" class="col-form-label col-sm-2">{{this.libelle}}</label>
        <div class="col-sm-10">
            <textarea name="regle" id="regle" class="form-control" cols="40" rows="3"></textarea>
        </div>
    </div>

    <div class="input-group">
        <span class="input-group-text">{{this.libelle}}</span>
        <textarea class="form-control custom-control" rows="5">ssd</textarea>

    </div>

</template>
<script>

export default {
    name: 'Regle',
    props: {
        nom: {required: true},
        libelle: {required: true}
    }
}
</script>

<style scoped>

</style>