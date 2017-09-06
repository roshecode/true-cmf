<template>
    <div></div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true
            }
        }
    }
</script>

<style scoped>
    > div {
        background: red;
    }
</style>
