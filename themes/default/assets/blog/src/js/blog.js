window.Vue = require('vue');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
var _ = require('lodash');

Vue.component('article-likes', require('./components/ArticleLikesComponent').default);

const app = new Vue({
    el: '#blog',
});
