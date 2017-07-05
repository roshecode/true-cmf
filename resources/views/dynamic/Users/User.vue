<template>
    <main>
        <div class="wrapper">
            <div class="popup">
                <header>
                    <div class="control">
                        <i class="icon-close"></i>
                        <i class="icon-fullscreen"></i>
                        <i class="icon-collapse"></i>
                    </div>
                    <h4>Please enter user data</h4>
                    <p>{{ `${user.lastName} ${user.firstName} ${user.middleName}` }}</p>
                </header>
                <form class="user" ref="user" :action="`api${$route.fullPath}`" @submit.prevent="post">
                    <section>
                        <label for="first-name">First name: </label>
                        <input id="first-name" name="firstName" type="text" v-model="user.firstName">
                        <label for="middle-name">Middle name: </label>
                        <input id="middle-name" name="middleName" type="text" v-model="user.middleName">
                        <label for="last-name">Last name: </label>
                        <input id="last-name" name="lastName" type="text" v-model="user.lastName">
                    </section>
                    <footer>
                        <input type="submit" value="Update">
                        <!--<button @click="animation.play({reverse: trigger = !trigger})">ANIMATE</button>-->
                        <button @click="animation.play()">ANIMATE</button>
                    </footer>
                </form>
            </div>
        </div>
    </main>
</template>

<script>
    import Vue from 'vue';
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
                this.$http.post(`/api/users/${this.$route.params.slug}`, new FormData(this.$refs.user));
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
            Vue.http.get(`/api/users/${to.params.slug}`).then(response => {
                response.json().then(data => {
                    next(vm => vm.$data.user = data);
                })
            });
        }
    }
</script>

<style lang="postcss">
    .popup {
        margin: auto;
    }

    .control {
        float: left;
        margin: -1rem .5rem .5rem -1rem;
    }

    [class^=icon] {
        display: block;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        margin: .5rem;
        transition: transform .2s;

        &:hover {
            cursor: pointer;
            transform: scale(1.2);
            box-shadow: 0 0 0.2rem #404040;
        }
    }

    .icon-close {
        background: tomato;

            background: radial-gradient(color(tomato lightness(+10%)), color(tomato lightness(-10%)));
        &:hover {
        }
    }

    .icon-fullscreen {
        background: yellow;

            background: radial-gradient(color(yellow lightness(+10%)), color(yellow lightness(-10%)));
        &:hover {
        }
    }

    .icon-collapse {
        background: yellowgreen;

            background: radial-gradient(color(yellowgreen lightness(+10%)), color(yellowgreen lightness(-10%)));
        &:hover {
        }
    }
</style>
