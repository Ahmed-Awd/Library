<template>
    <svg class="icon" :class="class" fill="none">
        <use :xlink:href="'#icon-' + name" />
    </svg>
</template>

<script>
    export default {
        props: {
            name: String,
            class: {
                type: String,
                default: 'w-8 h-8'
            }
        }
    }
</script>