<template>
    <input v-model="value" :disabled="disabled" ref="inputFloat" :class="cclass">
</template>

<script>

export default {
    name: 'UInputFloat',
    props: {
        modelValue: {},
        isPourc: {type: Boolean, required: false, default: false},
        disabled: {type: Boolean, required: false, default: false},
        class: {type: String, required: false, default: ''},
        fraction: {type: Boolean, required: false, default: false},
        maximumDigits: {required: false, default: 8},
    },
    data()
    {
        return {
            fractions: {
                0.333333: '1/3',
                0.166667: '1/6',
                0.142857: '1/7',
                0.111111: '1/9',
                0.666667: '2/3',
                0.285714: '2/7',
                0.222222: '2/9',
                0.428571: '3/7',
                1.333333: '4/3',
                0.571429: '4/7',
                0.444444: '4/9',
                1.666667: '5/3',
                0.833333: '5/6',
                0.714286: '5/7',
                0.555556: '5/9',
                0.857143: '6/7',
                2.333333: '7/3',
                1.166667: '7/6',
                0.777778: '7/9',
                2.666667: '8/3',
                1.142857: '8/7',
                0.888889: '8/9',
                1.285714: '9/7',
            },
            inError: false,
        };
    },
    emits: ['update:modelValue'],
    computed: {
        value: {
            get()
            {
                let value = this.modelValue;

                if (this.isPourc) {
                    value *= 100;
                }

                return this.floatToString(value);
            },
            set(value)
            {
                const numberPattern = /^-?\d*\.?\d+$/;

                // Vérifie si la chaîne correspond à l'expression régulière
                this.inError = (undefined !== value && '' !== value && !numberPattern.test(value.replace('/', '').replace(',', '').replace('.', '')));

                if (!this.inError) {
                    let resValue = this.stringToFloat(value);
                    if (this.isPourc) {
                        resValue /= 100;
                    }

                    if (undefined === resValue || (!isNaN(resValue) && isFinite(resValue))) {
                        this.$emit('update:modelValue', resValue);
                    }
                }
            }
        },
        cclass()
        {
            let cclass = this.class;

            if (this.inError) {
                cclass += ' is-invalid';
            }

            return cclass;
        },
    },
    methods: {
        floatToString(value)
        {
            if (undefined === value || null === value) {
                return undefined;
            }

            const test = Math.round(value * 1000000) / 1000000;
            if (this.fraction && undefined !== this.fractions[test]) {
                return this.fractions[test];
            }
            var locale = 'fr';
            var options = {minimumFractionDigits: 0, maximumFractionDigits: this.maximumDigits, useGrouping: false};
            var formatter = new Intl.NumberFormat(locale, options);

            return formatter.format(value);
        },


        stringToFloat(value)
        {
            if (null === value || '' === value || undefined === value) return undefined;

            if (value.indexOf('/') !== -1) {
                value = value.split('/');
                value = Util.stringToFloat(value[0]) / Util.stringToFloat(value[1]);
            } else {
                value = parseFloat(value.replace(',', '.'));
                //value = Math.round(value * (10^this.maximumDigits)) / (10^this.maximumDigits);
            }

            return value;
        },
    },
    mounted()
    {

    },
}

</script>
<style scoped>

input.is-invalid {
    background-color: #dc4c64;
}

</style>