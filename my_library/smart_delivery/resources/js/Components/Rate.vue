<template>
    <div class="flex">
        <icon name="star"
            class="w-5 h-5"
            :class="[
                rateValue >= i ? 'text-yellow-300 fill-current' : 'text-gray-600',
                this.disabled ? '' : 'cursor-pointer'
            ]"
            v-for="i in 5"
            :key="i"
            @mouseenter="mouseEnter(i)"
            @mouseleave="mouseLeave()"
            @click="rate"/>
    </div>
</template>

<script>
    export default {
        props: {
            modelValue: Number,
            disabled: Boolean,
        },

        data() {
            return {
                rateValue: this.modelValue
            };
        },

        methods: {
            rate() {
                if (this.disabled) {
                    return;
                }
                this.$emit('update:modelValue', this.rateValue)
                this.$emit('change', this.rateValue)
            },
            mouseEnter(index) {
                if (this.disabled) {
                    return;
                }
                this.rateValue = index;
            },
            mouseLeave() {
                if (this.disabled) {
                    return;
                }
                this.rateValue = this.modelValue;
            }
        }
    }
</script>