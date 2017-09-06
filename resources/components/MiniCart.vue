<template>
    <div class="minicart">
        <Motion tag="h4" :value="grandTotal">
            <template scope="motion">Total: <span>{{ motion.value.toFixed(2) }}<small>$</small></span></template>
        </Motion>
        <Motion :duration="700" :reverse="true">
            <template scope="motion">
                <transition-group tag="ul" name="fade-from-top" class="minicart-products-list">
                    <li v-for="(product, index) in products"
                        :key="product.id"
                        :style="{transform: motion.translateY(motion.value, index)}"
                        ref="list"
                    >
                        <!--v-motion="{motion, transform: motion.translateY(motion.value, index)}"-->
                        <figure>
                            <img :src="product.imageSrc" :alt="`${product.name} product`">
                            <figcaption>
                                <a href="#">{{ product.name }}</a>
                            </figcaption>
                        </figure>
                        <div class="price">
                            <small>{{ product.price.toFixed(2) }}$</small>
                            <Motion :value="product.price * product.quantity">
                                <template scope="motion">{{ motion.value.toFixed(2) }}$</template>
                            </Motion>
                        </div>
                        <div class="quantity"><small>✕</small>{{ product.quantity }}</div>
                        <i @click="motion.to(() => $refs.list[index], index);
                            onRemove($event, product, index)" class="icon xx-small"
                        >✕</i>
                    </li>
                </transition-group>
            </template>
        </Motion>
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex';
    import { REMOVE_FROM_CART } from '../store/mutationTypes';

    export default {
        name: 'MiniCart',

        computed: {
            ...mapState('Cart', ['products', 'grandTotal']),
        },

        data() {
            return {
                offset: 0,
                offsetIndex: 0,
            }
        },

        methods: {
            ...mapMutations('Cart', {
                removeFromCart: REMOVE_FROM_CART
            }),

            onRemove(event, product, index) {
                this.removeFromCart({product});
//                this.offset = event.target.parentElement.getBoundingClientRect().height + 4;
//                this.offsetIndex = index;
//                event.target.parentElement.style.position = 'absolute';
            },

            resetOffset() {
                this.offset = 0;
                console.log(this.offset);
            },
        },
    }
</script>

<style lang="postcss">
    .minicart {
        position: sticky;
        top: 0;
        color: #666;

        & > h4 {
            & > span {
                color: #666;
            }
        }
    }

    .minicart-products-list {
        /*display: grid;*/
        /*align-items: center;*/
        /*grid-template-columns: 1fr auto auto;*/

        & > li {
            display: flex;
            align-items: center;
            border-radius: .2rem;
            margin-bottom: .25rem;
            width: 100%;

            /*transition: background .3s;*/

            &:nth-child(odd) {
                background: white;
            }

            &:nth-child(even) {
                background: #f8f8f8;

                & > figure {
                    & > img {
                        background: #e2e2e2;
                    }
                }
            }

            & > figure {
                display: flex;
                align-items: center;
                flex-grow: 1;
                padding: .5rem;

                & > img {
                    max-width: 3rem;
                    margin: -.25rem;
                    padding: .5rem;
                    border-radius: .2rem;
                    background: #c3fff8;
                }

                & > figcaption {
                    margin-left: 1rem;
                }
            }

            & > .price {
                text-align: right;
            }

            & > .quantity {
                /*font-size: 1.5rem;*/
                margin-left: .5rem;
            }

            & > :--icon {
                align-self: flex-start;
                font-size: .8rem;
                line-height: 1.4;
                text-align: center;
                color: white;
                /*background: #666;*/
                background: rgba(102, 102, 102, 0.2);
                border-radius: 50%;
                margin: .5rem;
                transition: background .2s;

                &:hover {
                    cursor: pointer;
                    background: #aaa;
                }
            }
        }
    }
</style>
