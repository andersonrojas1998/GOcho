

require('./bootstrap');
window.Vue = require('vue');
import Swal from 'sweetalert2';
import axios from 'axios'
Vue.prototype.$http = axios;

import titleMixin from './mixins/titleMixin';
Vue.mixin(titleMixin);


import tblUser from "./components/users/tblUser.vue";
import tblTask from "./components/task/tblTask.vue";
const app = new Vue({
    el: '#app',
    components: {
        tblUser,
        tblTask
    },
});
