<template>
    <div class="v-adaptive">
        <slot :attached-items="attachedItems" :detached-items="detachedItems" :show-more="showMore">
            <ul>
                <li v-for="item in attachedItems">{{ item }}</li>
                <li v-show="showMore" ref="popup">
                    <slot name="placeholder">
                        <span>More</span>
                    </slot>
                    <ul>
                        <li v-for="item in detachedItems">{{ item }}</li>
                    </ul>
                </li>
            </ul>
        </slot>
    </div>
</template>

<script>
//    var settings = $.extend({
//        includeMargin: true,
//        itemTag: 'li'
//    }, userSettings);
//
//    var menu = $(this),
//        menuItems = menu.find(settings.itemTag),
//        menuItemsWidth = 0,
//        popup = $('<ul>'),
//        separateIndex = 0,
//        includeMargin = settings.includeMargin;
//    menuItems.append(popup);
//
//    for (var i = 0; i < menuItems.length; ++i) {
//        menuItemsWidth += menuItems.eq(i).outerWidth(includeMargin);
//    }
//
//    function addToPopup() {
//        ++separateIndex;
//        var toPopup = menuItems.eq(-separateIndex - 1);
//        menuItemsWidth -= toPopup.outerWidth(includeMargin);
//        popup.append(toPopup.detach());
//    }
//
//    function getFromPopup() {
//        var fromPopup = popup.find('li').last();
//        var fromPopupWidth = fromPopup.outerWidth(includeMargin);
//        if (menu.outerWidth(includeMargin) > menuItemsWidth + fromPopupWidth) {
//            --separateIndex;
//            menuItemsWidth += fromPopupWidth;
//            menuItems.last().before(fromPopup.detach());
//        }
//    }
//
//    function firstMoveToPopup() {
//        if (menuItemsWidth > menu.outerWidth(includeMargin)) {
//            addToPopup();
//            firstMoveToPopup();
//        }
//    }
//    firstMoveToPopup();
//
//    $(window).resize(function () {
//        menuItemsWidth > menu.outerWidth(includeMargin) ? addToPopup() : getFromPopup();
//    });

    export default {
        name: 'adapt-list',

        props: {
            items: {
                type: Array,
                default: () => []
            },
            includeMargin: {
                type: Boolean,
                default: true
            },
        },

        data() {
            return {
                attachedItems: this.items,
                detachedItems: [],
                showMore: false,
            }
        },

        methods: {
            getWidth(element) {
                return element.getBoundingClientRect().width;
            },
        },

        beforeMount() {

            this.$nextTick(() => {
                let container = this.$el.firstElementChild,
                    elements = container.children,
                    attachedElementsWidth = container.getBoundingClientRect().width,
                    previousAttachedElementsWidth = attachedElementsWidth,
                    onResize = () => {
                    let containerRect = container.getBoundingClientRect(),
                        containerWidth = containerRect.width -
                            (this.showMore ? elements[elements.length - 1].getBoundingClientRect().width : 0);

                    if (this.attachedItems.length
                        && (attachedElementsWidth =
                            elements[elements.length - 2].getBoundingClientRect().right - containerRect.left)
                        > containerWidth
                    ) {
                        previousAttachedElementsWidth = attachedElementsWidth;
                        this.detachedItems.splice(0, 0,
                            this.attachedItems.pop()
                        );
                        this.showMore = true;
                        this.$nextTick(onResize);
                    } else if (this.detachedItems.length && (containerWidth >= previousAttachedElementsWidth)) {
                        this.attachedItems.push(
                            ...this.detachedItems.splice(0, 1)
                        );
                        if (this.detachedItems.length) {
                            this.$nextTick(onResize);
                        } else {
                            this.showMore = false;
                        }
                    }
                };
                window.addEventListener('resize', onResize);
                onResize();
            });
        }
    }
</script>

<style lang="postcss" scoped>
    ul {
        position: relative;
        display: flex;
        list-style: none;
        /*flex-wrap: wrap;*/

        /*background: #d2523a;*/
        /*color: wheat;*/
        background: #07887a;
        color: #acffe2;

        & > li {
            padding: 1rem;

            &:hover {
                /*background: wheat;*/
                /*color: #d2523a;*/
                background: #29a598;
            }

            &:last-of-type {
                position: relative;
                margin-left: auto;

                & > ul {
                    position: absolute;
                    display: block;
                    top: 100%;
                    right: 0;
                }
            }
        }
    }
</style>

<style>
    ul + header {
        margin: 0 !important;
    }
</style>
