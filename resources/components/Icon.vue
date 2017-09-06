<template>
    <i class="icon">
        <svg>
            <use :xlink:href="`#icon-${name}`"></use>
        </svg>
    </i>
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
