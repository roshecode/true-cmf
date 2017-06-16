<template>
    <form class="user" ref="user" @submit.prevent="post">
        <h2>{{ `${user.lastName} ${user.firstName} ${user.middleName}` }}</h2>
        <label for="first-name">First name: </label>
        <input id="first-name" name="firstName" type="text" :value="user.firstName">
        <label for="middle-name">Middle name: </label>
        <input id="middle-name" name="middleName" type="text" :value="user.middleName">
        <label for="last-name">First name: </label>
        <input id="last-name" name="lastName" type="text" :value="user.lastName">
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

        methods: {
            post() {
                fetch(`/api${this.$route.fullPath}`, {
                    method: 'POST',
                    body: new FormData(this.$refs.user)
                });
            }
        },

        beforeRouteEnter(to, from, next) {
            fetch(`/api${to.fullPath}`).then(response => {
                response.json().then(data => {
                    next(vm => vm.user = data);
                });
            });
        }
    }
</script>

<style lang="postcss">
    .user {
        display: grid;
        grid-template-columns: auto 1fr;
        grid-gap: .5rem;

        & > :--first-last-child {
            grid-column: span 2;
        }

        & > :--submit {
            margin-top: 1rem;
        }
    }
</style>
