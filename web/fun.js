let ajaxFun = {
    link: [
        { sel: '.block_menu', to: 'content' },
        { sel: '.block_categories', to: 'content', init: 'form' },
        { sel: '.block_cart', to: 'content' }
    ],
    load: [
        { sel: '.btn_callback', to: 'content', init: 'form' },
        { sel: '.btn_product-rm', to: 'content', init: 'form' }
    ],
    send: [
        { sel: '.product-send-form', to: 'products-count total-cost', type: 'json' }
    ]
};