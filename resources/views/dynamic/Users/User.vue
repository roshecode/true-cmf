<template>
    <main>
        <adapt-list :items="['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight8888888', 'nine']">
            <template scope="list">
                <ul data-attached>
                    <li v-for="item in list.attachedItems">{{ item }}</li>
                </ul>
                <button v-show="list.showMore">more</button>
                <ul>
                    <li v-for="item in list.detachedItems">{{ item }}</li>
                </ul>
            </template>
        </adapt-list>
        <div class="overlay">
            <div class="modal">
                <header>
                    <div class="control">
                        <i class="icon-close"></i>
                        <i class="icon-fullscreen"></i>
                        <i class="icon-collapse"></i>
                    </div>
                    <h4>Please enter user data</h4>
                    <p>{{ `${user.lastName} ${user.firstName} ${user.middleName}` }}</p>
                </header>
                <form class="user" ref="user" :action="`api/users/${slug}`" @submit.prevent="post">
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
                        <input type="button" value="Update">
                    </footer>
                </form>
            </div>
        </div>
    </main>
</template>

<script>
    import Vue from 'vue';
    import AdaptList from '../../../components/AdaptList';

    export default {
        components: {
            AdaptList
        },

        api: {
            user: {
                methods: ['GET', 'POST'],
                url: '/users/:slug'
            },
            address: '/addresses/user/:slug/'
        },

        metaInfo() {
            return {
                title: `${this.user.firstName} ${this.user.lastName} profile`
            }
        },

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
                this.$http.post(`/api/users/${this.slug}`, new FormData(this.$refs.user));
            }
        },

        beforeRouteEnter(to, from, next) {
            Vue.http.get(`/api/users/${to.params.slug}`).then(response => {
//                response.json().then(data => {
//                    next(vm => vm.$data.user = data);
//                })
                next(vm => vm.$data.user = response.body);
            });
        },

        mounted() {
            console.log(this.$refs);
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
            /*transform: scale(1.1);*/
            box-shadow: 0 0 0.2rem #404040;
        }
    }

    .icon-close {
        background: tomato;
        /*border: .2rem solid tomato;*/
        /*border-radius: 50%;*/
        /*transform: scale(1.12);*/

        &:hover {
            /*background: radial-gradient(color(tomato lightness(+10%)), color(tomato lightness(-10%)));*/
        }
    }

    .icon-fullscreen {
        /*background: gold;*/
        border: .2rem solid gold;
        /*border-radius: .2rem;*/
        /*transform: scale(1.05);*/
        transition: border-radius .3s;

        &:hover {
            border-radius: .2rem;
            /*background: radial-gradient(color(gold lightness(+10%)), color(gold lightness(-10%)));*/
        }
    }

    .icon-collapse {
        /*background: yellowgreen;*/
        /*height: 0;*/
        border: .2rem solid yellowgreen;
        /*border-radius: .2rem;*/
        /*transform: scale(1.1) rotateX(30deg);*/

        &:hover {
            /*background: radial-gradient(color(yellowgreen lightness(+10%)), color(yellowgreen lightness(-10%)));*/
        }
    }
</style>
