import Vue from 'vue'
import Router from 'vue-router'
import TheIndexUser from './views/users/TheIndexUsers.vue';
import Redirect from './views/error/notFound.vue';

Vue.use(Router);
export default new Router({
    routes: [

        {
            path: '/usuarios/inicio',
            name: 'usuario',
            component: TheIndexUser
        },
        
        /*{
            path: '/almacen/despachos/:id/:idOrder',
            name: 'despachos',
            component: TheIndexDispatch,
            props: true
        },*/
        
        
        
        
        {
            path: '*',
            component: Redirect
        },
        {
            path: '',
            redirect: '/home'
        },
    ],
    mode: 'history',
    history: true
})
