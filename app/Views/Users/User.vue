<template>
    <form class="user">
        <h2>{{ `${user.lastName} ${user.firstName} ${user.middleName}` }}</h2>
        <label for="firstName">First name: </label>
        <input id="firstName" type="text" :value="user.firstName">
        <label for="middleName">Middle name: </label>
        <input id="middleName" type="text" :value="user.middleName">
        <label for="lastName">First name: </label>
        <input id="lastName" type="text" :value="user.lastName">
        <input type="submit" value="update">
    </form>
</template>

<script>
    export default {
        props: {
            slug: Number | String
        },

        data() {
            return {
                user: {}
            }
        },

        beforeRouteEnter(to, from, next) {
            console.log(to);
            fetch(`/api${to.fullPath}`).then(response => {
                response.json().then(data => {
                    console.log(data);
                    next(vm => vm.user = data);
                });
            });
        }
    }
</script>

<style lang="postcss">
    /*@import "~styles/root.pcss";*/

    .user {
        display: grid;
        grid-template-columns: auto 1fr;
        grid-gap: .5rem;

        & > *:first-child, & > *:last-child {
            grid-column: span 2;
        }

        /*& > :--submit {*/
            /*margin-top: 1em;*/
        /*}*/
    }
</style>
