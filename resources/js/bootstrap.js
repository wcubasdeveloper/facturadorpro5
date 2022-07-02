import Vue from 'vue';
window._ = require('lodash');
window.moment = require('moment');
window.Popper = require('popper.js').default;

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}

import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    window.headers_token = {
        'X-CSRF-TOKEN': token.content,
    }
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

Vue.prototype.$http = axios;



Vue.prototype.$setStorage =   function(name,obj){
    localStorage.setItem(name, JSON.stringify(obj));
};
Vue.prototype.$getStorage = function(name){
    return JSON.parse(localStorage.getItem(name));
};

require('./vendor/perfect-scrollbar.jquery.min')
require('./vendor/sidebarmenu')
require('./vendor/waves')
require('./vendor/custom')

$(function () {
    const listElements = document.getElementsByClassName('nav-active');
    if (listElements.length > 0) {
        listElements[0].scrollIntoView();
    }
});


const mercadopago = window.Mercadopago; 

if(mercadopago)
{
    mercadopago.setPublishableKey(window.token_mercado_pago);
    mercadopago.getIdentificationTypes();
}
