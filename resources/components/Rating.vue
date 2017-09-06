<template>
    <ul class="rating" @mouseleave="reset()">
        <li v-for="i in max"
            :class="[{select: i <= currentValue}, {hover: i <= hoveredValue}]"
            @mouseover="onMouseover(i)"
            @click="onClick(i)"
        >
            <span>{{ i }}</span>
        </li>
        <li>
            <svg height="0">
                <filter id="f1">
                    <feGaussianBlur stdDeviation="3"/>
                </filter>
                <filter id="f3">
                    <feConvolveMatrix filterRes="100 100" style="color-interpolation-filters:sRGB"
                                      order="3" kernelMatrix="0 -1 0   -1 4 -1   0 -1 0" preserveAlpha="true"/>
                </filter>
                <filter id="dilate_shape">
                    <feMorphology operator="dilate" in="SourceGraphic" radius="5" />
                </filter>
                <filter id="pictureFilter">
                    <feMorphology operator="erode" radius="2" />
                </filter>
            </svg>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            max: {
                type: Number,
                default: 5
            },

            value: {
                type: Number,
                default: 1
            }
        },

        data() {
            return {
                currentValue: this.value,
                hoveredValue: 0,
            }
        },

        methods: {
            reset() {
                this.hoveredValue = 0;
//                this.hovered = new Array(this.max);
            },

            onMouseover(index) {
                this.hoveredValue = index;
            },

            onClick(index) {
                this.currentValue = index;
            }
        }
    }
</script>

<style lang="postcss">
    .rating {
        display: flex;
        list-style: none;
        text-align: center;
        padding: .25rem;
        color: white;

        & > li, & > li > span {
            clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
        }

        & > li {
            text-align: center;
            margin: .15rem;
            width: 1.2rem;
            height: 1.2rem;
            color: transparent;
            display: flex;
            background: #d2d2d2;

            &.select {
                background: #e4be01;

                & > span {
                    background: #efd761;
                }
            }

            &:--hover {
                cursor: pointer;
                background: #e4be01;

                & > span {
                    background: #e4be01;
                }
            }

            & > span {
                display: block;
                margin: auto;
                width: .8rem;
                height: .8rem;
                background: whitesmoke;
                transition: background .2s;
            }
        }
    }
</style>
