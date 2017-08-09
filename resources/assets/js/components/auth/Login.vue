<template>
    <div>
        <h2>Login</h2>
        <p v-if="$route.query.redirect">
            You need to login first.
        </p>
        <form @submit.prevent="login">
            <div class="row">
                <div class="col-md-2">
                    <label>E-mail</label>
                    <input v-model="email" placeholder="E-mail" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>Password</label>
                    <input v-model="pass" placeholder="Password" type="password" class="form-control">
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary" type="submit">Login</button>
                </div>
            </div>
            <label></label>
            <p v-if="error" class="error">Bad login information</p>
        </form>
    </div>
</template>

<script type="text/ecmascript-6">
    import auth from '../../auth'

    export default {
        data () {
            return {
                email: '',
                pass: '',
                error: false
            }
        },
        methods: {
            login () {
                auth.login(this.email, this.pass, success => {
                    if (!success) {
                        this.error = true
                    } else {
                        this.error = false;
                        this.$router.replace(this.$route.query.redirect || '/')
                    }
                })
            }
        }
    }
</script>

<style>
    .error {
        color: red;
    }
</style>
