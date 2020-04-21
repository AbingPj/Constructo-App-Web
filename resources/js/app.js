
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from 'vue-router';

import Datetime from  'vue-datetime';

import 'vue-datetime/dist/vue-datetime.css';
window.Vue = require('vue');
window.Vue.use(Datetime);
window.Vue.use(VueRouter);
Vue.prototype.$eventBus = new Vue();


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import LoginPage from './components/LoginPage';
import CreateAccount from './components/CreateAccountPage';
import AdminPage from './components/AdminPage';
import StoreDashboard from './components/StoreDashboard';
import CustomerDashboard from './components/CustomerDashboard';
import CustomerMainDashboard from './components/CustomerDashboard';
import CustomerStoreVisitPage from './components/CustomerStoreVisitPage';
import CustomerCart from  './components/CustomerCart';
import CustomerOrderList from  './components/CustomerOrderList';
import CustomerMyAccount from  './components/CustomerMyAccount';
import AdminUsers from './components/AdminUsers';
import AdminAccount from './components/AdminAccount';
import StoreAccount from './components/StoreAccount';
import CustomerMessages from './components/CustomerMessages';
import WelcomePage from './components/WelcomePage';
import StoreSingleOrder from './components/StoreSingleOrder';
import CustomerSingleOrder from './components/CustomerSingleOrder';
import AdminMyAccount from './components/AdminMyAccount';

const routes = [
    {
    path : '/',
    component: {WelcomePage : WelcomePage}
    },{
        path: '/Signup',
        name : 'CreateAccount',
        component : CreateAccount
    },{
        path: '/Login',
        name : 'LoginPage',
        component : LoginPage
    },{
        path : '/WelcomeAdmin',
        name : 'AdminPage',
        component : AdminPage
    },{
        path : '/WelcomeBackStore',
        name : 'StoreDashboard',
        component : StoreDashboard
    },{
        path : '/WelcomeCustomer',
        name : 'CustomerDashboard',
        component  : CustomerDashboard
    },{
        path : '/YourDashboard',
        name : 'CustomerMainDashboard',
        component: CustomerMainDashboard
    },{
        path : '/EnjoyShopping',
        name : 'CustomerStoreVisitPage',
        component: CustomerStoreVisitPage
    },{
        path : '/YourCart',
        name : 'CustomerCart',
        component: CustomerCart
    },{
        path : '/CustomerOrderList',
        name : 'CustomerOrderList',
        component: CustomerOrderList
    },{
        path : '/MyAccount',
        name : 'CustomerMyAccount',
        component: CustomerMyAccount
    },{
        path : '/OurTrustedUsers',
        name : 'AdminUsers',
        component: AdminUsers
    },{
        path : '/MyaccounT',
        name : 'AdminAccount',
        component: AdminAccount
    },{
        path : '/MyStore',
        name : 'StoreAccount',
        component: StoreAccount
    },{
        path : '/CustomerMessages',
        name : 'CustomerMessages',
        component: CustomerMessages
    },{
        path : '/singleOrder',
        name : 'StoreSingleOrder',
        component: StoreSingleOrder
    },{
        path : '/CustomersingleOrder',
        name : 'CustomerSingleOrder',
        component: CustomerSingleOrder
    },{
        path: '/AdminMyAccount',
        name : 'AdminMyAccount',
        component : AdminMyAccount
    }
];
const router = new VueRouter({routes});
const app = new Vue({router}).$mount('#app');
