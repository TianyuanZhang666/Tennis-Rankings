
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import ElementUI from 'element-ui';

import 'element-ui/lib/theme-chalk/index.css';

Vue.use(ElementUI);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('home', require('./components/MainComponent.vue'));

const _request = function (param) {
    return new Promise((resolve,reject) => {
        axios.request({
            url: param.url,
            params: {
                ...param.arg,
            },
            }).then(res => {
                resolve(res.data)
            }).catch(error => {
            console.log();
            console.log(error.response.headers);
            if (error.response.status === 422) {
                Vue.prototype.$notify({
                    title: "error",
                    message: error.response.data.message
                })
            }
                reject(error)
            })
        })
}
Vue.prototype.$http = _request;

const app = new Vue({
    el: '#app'
});
