<template>
    <div class="popup">
        <header>
            <h4>Please enter user data</h4>
            <p>{{ `${lastName} ${firstName} ${middleName}` }}</p>
        </header>
        <form class="user" ref="user" :action="`api${$route.fullPath}`" @submit.prevent="post">
            <label for="first-name">First name: </label>
            <input id="first-name" name="firstName" type="text" v-model="firstName">
            <label for="middle-name">Middle name: </label>
            <input id="middle-name" name="middleName" type="text" v-model="middleName">
            <label for="last-name">Last name: </label>
            <input id="last-name" name="lastName" type="text" v-model="lastName">
            <footer>
                <input type="submit" value="Update">
                <!--<button @click="animation.play({reverse: trigger = !trigger})">ANIMATE</button>-->
                <button @click="animation.play()">ANIMATE</button>
            </footer>
        </form>
    </div>
</template>

<script>
    import Animation from './../../../resources/scripts/animation';

    export default {
        props: {
            slug: Number | String
        },

        data() {
            return {
                trigger: true,
                animation: null,
                firstName: '',
                middleName: '',
                lastName: ''
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
//            this.animation.animate({marginTop: {start: 16}}, {
                duration: 1000,
                easing: Animation.easing.easeOutBounce,
                params: [[.8, .2], [.2, .8]],
                // params: [[0, 0], [.192, 1.68], [-.58, -1], [1, 1]],
                process(timeFraction) {
                    console.log(this.progress);
                }
            });
        },

        beforeRouteEnter(to, from, next) {
            fetch(`/api${to.fullPath}`).then(response => {
                response.json().then(data => {
                    next(vm => Object.assign(vm.$data, data));
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
