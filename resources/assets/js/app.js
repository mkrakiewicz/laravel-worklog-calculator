/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * We will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */
import VueRouter  from 'vue-router'
import router     from './router'
import Vue        from 'vue'

// import auth from './auth'
import App from './components/App.vue'
// import About from './components/About.vue'
// import Dashboard from './components/Dashboard.vue'
// import Login from './components/Login.vue'

Vue.use(VueRouter);

// lazy load components
// const Room = (resolve) => require(['./components/Room.vue'], resolve)


//import modals from './modals';

Vue.component('bootstrap-modal',require('./components/modal/BootstrapModal.vue'));


/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    // replace the content of <div id="app"></div> with App
    render: h => h(App),
    //components:
    //{
    //    'bootstrap-modal' : require('./components/modal/BootstrapModal.vue')
    //}
});


//var data = {
//    grant_type: 'password',
//    client_id: 1,
//    //client_secret: '18Z87q9BnOQSYhHivPidEz6CKe5B7VUcdjnxq5nM',
//    username: '1asd@asd.pl',
//    password: '123456'
//};

//Vue.http.post('/oauth/token', data).then(function (result) {
//    var token = _.get(result, 'body.access_token');
//    alert(token);
//}).catch(function (result) {
//    var token = _.get(result, 'body.access_token');
//    alert('erpr');
//});
