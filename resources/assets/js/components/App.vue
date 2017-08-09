<template>
    <div id="app">
        <h1>Input Of Calories</h1>
        <ul class="nav nav-tabs">
            <li v-if="!loggedIn">
                <router-link to="/dashboard">Dashboard</router-link>
            </li>
            <li v-if="loggedIn && acl.access_meals">
                <router-link to="/list-of-meals">List of Meals</router-link>
            </li>
            <li v-if="loggedIn && acl.access_users">
                <router-link to="/users">Users</router-link>
            </li>
            <li v-if="loggedIn && acl.access_user_settings">
                <router-link to="/settings">Settings</router-link>
            </li>
            <li>
                <router-link v-if="loggedIn" to="/logout">Log out</router-link>
                <router-link v-if="!loggedIn" to="/login">Log in</router-link>
            </li>
            <li v-if="!loggedIn">
                <router-link to="/register">Register</router-link>
            </li>
            <li class="userInfo pull-right" v-if="loggedIn">
                <a>{{user.name}} - {{user.email}}</a>
            </li>
        </ul>

        <transition name="fade">
            <p v-if="message" class="alert alert-success">{{message}}</p>
        </transition>
        <div id="routerView">
            <template v-if="$route.matched.length">
                <router-view></router-view>
            </template>
            <template v-else>
                <p>You are logged {{ loggedIn ? 'in' : 'out' }}.</p>
            </template>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import auth from '../auth'
    import flash from '../flash'

    export default {
        data () {
            return {
                loggedIn: auth.loggedIn(),
                acl: auth.acl(),
                message: flash.getMessage(),
                user: auth.user()
            }
        },
        created () {
            var that = this;
            auth.onChange = (loggedIn, acl) => {
                that.loggedIn = loggedIn
                that.acl = auth.acl();
                that.user = auth.user();
            };
            flash.onChange = function () {
                that.message = flash.getMessage();
                setTimeout(function () {
                    that.message = false;
                    flash.clearWithoutEvent();
                }, 3000);
            }
        }
    }
</script>
