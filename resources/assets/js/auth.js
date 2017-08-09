/* globals localStorage */
// import $http from 'vue-resource';
import Vue from 'vue';
import VueResource from 'vue-resource';
Vue.use(VueResource);

export default {
    login (email, pass, cb) {
        cb = arguments[arguments.length - 1]
        if (localStorage.token) {
            this.setHeaders();
            if (cb) cb(true)
            this.onChange(true)
            return
        }
        var data = {
            grant_type: 'password',
            client_id: window.ClientId,
            client_secret: window.ClientSecret,
            username: email,
            password: pass
        };
        var that = this;
        Vue.http.post('/oauth/token', data).then(function (result) {
            var token = _.get(result, 'body.access_token');
            if (token) {
                localStorage.token = token;
                localStorage.acl = JSON.stringify(_.get(result, 'body.acl', {}));
                localStorage.user = JSON.stringify(_.get(result, 'body.user', {}));
                that.setHeaders();
                cb(true);
                that.onChange(true)
            }
        }).catch(function (result) {
            cb(false);
            that.onChange(false)
        });

    },

    register(formData, callback) {
        this.$http.post('/api/users', formData, callback);
    },

    getToken () {
        return localStorage.token
    },

    logout (cb) {
        delete localStorage.token
        delete localStorage.acl
        delete localStorage.user
        if (cb) cb()
        this.onChange(false, {})
    },

    acl() {
        var aclJson = _.get(localStorage, 'acl', "{}");
        return JSON.parse(aclJson);
    },

    user() {
        var user = _.get(localStorage, 'user', "{}");
        return JSON.parse(user);
    },


    loggedIn () {
        return !!localStorage.token
    },

    onChange (loggedIn, acl) {
    },

    setHeaders() {
        Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.token;
        Vue.http.headers.common['Content-type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';
        Vue.http.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    }
}


