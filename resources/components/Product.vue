<template>
    <div class="product" data-type="simple">
        <figure>
            <img :src="imageSrc" :alt="name + ' product'">
            <figcaption>{{ name }}</figcaption>
        </figure>
        <rating :value="rating"></rating>
        <button @click="addToCart({product: $props})">Add to cart</button>
        <span class="price">{{ price }}<small>{{ currency }}</small></span>
        <icon name="heart"></icon>
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex';
    import { ADD_TO_CART, REMOVE_FROM_CART } from '../store/mutationTypes';
    import Rating from './Rating.vue';

    export default {
        inheritAttrs: false,

        components: {
            Rating
        },

        props: {
            id: {
                type: Number,
                required: true
            },

            name: {
                type: String,
                default: 'Unknown'
            },

            imageSrc: {
                type: String,
                default: '/resources/images/thumb.png'
            },

            price: {
                type: Number,
                default: 0
            },

            currency: {
                type: String,
                default: '$',
                validator: v => v.length <= 3
            },

            rating: Number
        },

        computed: {
            ...mapState('Cart', {
                cartProducts: state => state.products
            }),
        },

        methods: {
            ...mapMutations('Cart', {
                addToCart: ADD_TO_CART,
                removeFromCart: REMOVE_FROM_CART
            }),
        },
    }
</script>

<style lang="postcss">
    .product {
        display: grid;
        /*align-items: end;*/
        grid-template:
                "figure figure figure"  1fr
                "rating rating rating"
                "price  wish   compare"
                "add    add    add"     /
                1fr     auto   auto;
        border: solid .2rem #f9f9f9;
        border-radius: .25rem;
        background: whitesmoke;
        transition: box-shadow .2s, transform .2s;

        &:hover {
            /*border: solid .2rem #dbdbdb;*/
            box-shadow: 0 0 .4rem #bbb;
            transform: scale(1.02);

            & > figure {
                & > img {
                }
            }
        }

        & > figure {
            grid-area: figure;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            justify-self: stretch;
            padding: .5rem;
            background: white;

            & > img {
                display: block;
                margin-top: auto;
                width: 100%;
                background: white;
                transition: transform .4s;
            }

            & > figcaption {
                /*position: absolute;*/
                /*font-size: 1.2rem;*/
                text-align: center;
                /*text-transform: uppercase;*/
                padding: .5rem 0;
                margin-top: auto;
                width: 100%;
                background: color(white alpha(.8));
                /*color: #333;*/
                color: #666;
            }
        }

        &:nth-child(odd) {
            & > figure {
                & > img {
                    /*filter: invert(1);*/
                    /*background: black;*/
                }
            }
        }

        & > .rating {
            grid-area: rating;
            justify-content: flex-end;
            /*margin-top: -2rem;*/
            background: linear-gradient(white, whitesmoke);
        }

        & .price {
            grid-area: price;
            padding: 0 .5rem;
            /*font-size: 2rem;*/
            font-size: 1.4rem;
            /*color: #444;*/
            color: #666;
        }

        & .icon {
            cursor: pointer;
            grid-area: wish;
            align-self: center;
            margin: 0 .5rem -.25rem;
            width: 1rem;
            height: 1rem;
            fill: #ff826c;
            transition: transform .2s;

            &:hover {
                transform: scale(1.2);
            }
        }

        & button {
            grid-area: add;
            width: 100%;
            border: solid 1px #40e0ce;
            /*background: #00cab3;*/
            background: white;
            color: #13bfaa;
            transition: background .2s, color .2s;

            &:hover {
                border: solid 1px #3cd1c0;
                /*background: #31D2C1;*/
                background: #40e0ce;
                color: white;
            }
        }
    }
</style>
