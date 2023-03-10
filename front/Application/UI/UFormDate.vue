<template>
    <div class="mb-2">
        <label :for="id ? id : name" class="form-label">{{ label }}</label>
        <input type="date" :name="name" :id="id ? id : name" class="form-control" v-model="dateVal" :disabled="disabled"/>
    </div>
</template>

<script>
export default {
    name: "UFormDate",
    props: {
        id: {type: String, required: false},
        name: {type: String, required: true},
        label: {type: String, required: true, default: 'Inconnu'},
        modelValue: {required: true, default: undefined},
        disabled: {type: Boolean, required: false, default: false},
    },
    data()
    {
        return {
            dateVal: undefined
        };
    },
    watch: {
        modelValue: function (val) {
            let value = val;
            if (val instanceof Date) {
                value = Util.dateToString(val);
            }
            if (val instanceof String) {
                value = val.slice(0, 10);
            }

            this.dateVal = value;
        },
        dateVal: function (val) {
            this.$emit("update:modelValue", new Date(val));
        },
    }
}
</script>

<style scoped>

</style>