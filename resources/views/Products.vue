<template>
    <div class="page">
        <div class="pills">
            <ul>
                <li class="pill">pillella 1</li>
                <li class="pill">pillullella 2</li>
                <li class="pill">pillulllla 3</li>
                <li class="pill">pilluella 4</li>
                <li class="pill">pillullella 5</li>
            </ul>
        </div>
        <header>
            <h3>Here will be header</h3>
            <!--<mini-cart></mini-cart>-->
        </header>
        <aside>
            <p>Your cart</p>
            <mini-cart></mini-cart>
        </aside>
        <section>
            <header>Products header</header>
            <section class="products-list">
                <product v-for="product in products" :key="product.id" v-bind="product"></product>
                <!--<pre v-html="products" style="color: black"></pre>-->
            </section>
            <footer>Products footer</footer>
        </section>
        <footer>
            <div class="link-blocks">
                <ul>
                    <li><a href="#">Here will be footer content</a></li>
                    <li><a href="#">Here will be footer content</a></li>
                    <li><a href="#">Here will be footer content</a></li>
                </ul>
                <ul>
                    <li><a href="#">Here will be footer content</a></li>
                    <li><a href="#">Here will be footer content</a></li>
                    <li><a href="#">Here will be footer content</a></li>
                </ul>
                <ul>
                    <li><a href="#">Here will be footer content</a></li>
                    <li><a href="#">Here will be footer content</a></li>
                    <li><a href="#">Here will be footer content</a></li>
                </ul>
            </div>
            <p>2017 @ Copyright All rights reserved.</p>
        </footer>
    </div>
</template>

<script>
    import Product from '../components/Product.vue';
    import MiniCart from '../components/MiniCart.vue';

    export default {
        components: {
            Product,
            MiniCart
        },

        data() {
            return {
                products: []
            }
        },

        created() {
            this.$http.get('products', {params: {page: 2, limit: 20}}).then(response => {
                console.log(response);
                this.products = Array.isArray(response.data) ? response.data : null;
            });
        }
    }
</script>

<style lang="postcss" scoped>
    .pills {
        grid-column: 2;
        max-width: 300px;
    }

    :--heading {
        font-weight: normal;
    }

    .page {
        /*font-family: 'Raleway', sans-serif;*/
        display: grid;
        grid-template-columns: minmax(auto, 25%) 1fr;
        grid-template-rows: auto 1fr auto;
        height: 100%;

        color: black;

        & > header {
            display: flex;
            justify-content: space-between;
            grid-column: span 2;
            background: #00cab3;
            color: white;
        }

        & > footer {
            grid-column: span 2;
            background: #eaeaea;
            color: #707070;

            & > .link-blocks {
                display: flex;
                justify-content: space-between;

                & > ul {
                    list-style: none;
                    padding: .5rem;
                }
            }
        }
    }

    aside {
        background: whitesmoke;
    }

    section {
        display: flex;
        flex-direction: column;
        background: white;

        & > section {
            flex-grow: 1;
        }
    }

    .products-list {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 1rem;
    }

</style>

<doc lang="md">
    #This is products component
</doc>
