<template>
    <div class="popup">
        <header>
            <h4>Please enter user data</h4>
            <p>{{ `${user.lastName} ${user.firstName} ${user.middleName}` }}</p>
        </header>
        <form class="user" ref="user" :action="`api${$route.fullPath}`" @submit.prevent="post">
            <label for="first-name">First name: </label>
            <input id="first-name" name="firstName" type="text" v-model="user.firstName">
            <label for="middle-name">Middle name: </label>
            <input id="middle-name" name="middleName" type="text" v-model="user.middleName">
            <label for="last-name">Last name: </label>
            <input id="last-name" name="lastName" type="text" v-model="user.lastName">
            <footer>
                <input type="submit" value="Update">
                <!--<button @click="animation.play({reverse: trigger = !trigger})">ANIMATE</button>-->
                <button @click="animation.play()">ANIMATE</button>
            </footer>
        </form>
    </div>
</template>

<script>
    import Animation from './../../../scripts/animation';

    export default {
        api: {
            user: {
                methods: ['GET', 'POST'],
                url: '/users/:slug'
            },
            address: '/addresses/user/:slug/'
        },

        props: {
            slug: Number | String
        },

        data() {
            return {
                trigger: true,
                animation: null,
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

        mounted() {
            this.animation = new Animation({el: '.popup'});
//            this.animation.animate({maxWidth: 750}, {
            this.animation.animate({maxWidth: 550, height: 400}, {
//            this.animation.animate([{marginTop: 50}, {marginLeft: 400}], {
//            this.animation.animate({marginTop: {start: 16}}, {
                duration: 1000,
                easing: Animation.easing.easeOutExpo,
//                params: [[.8, .2], [.2, .8]],
                 params: [[0, 0], [1.92, .68], [-1.58, -0], [1, 1]],
                process(timeFraction) {
                    console.log(this.progress);
                }
            });
        },

        beforeRouteEnter(to, from, next) {
            fetch(`/api${to.fullPath}`).then(response => {
                response.json().then(data => {
                    next(vm => vm.$data.user = data);
                });
            });
        }
    }
</script>

<style lang="postcss">
    .popup {
        margin: auto;
    }
</style>
