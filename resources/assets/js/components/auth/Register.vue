<template>
    <div class="container">
        <!--<span v-if="message" class="help-block">-->
        <!--<strong> {{ message }} </strong>-->
        <!--</span>-->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" @submit.prevent="register">
                            <div v-for="(fieldName, fieldIndex) in fields" class="form-group">
                                <label :for="fieldIndex" class="col-md-4 control-label">{{fieldName}}</label>
                                <div class="col-md-6">
                                    <input v-if="fieldIndex.indexOf('password') === -1" type="text"
                                           class="form-control" v-model="form[fieldIndex]"
                                           :name="fieldIndex" required>
                                    <input v-else type="password"
                                           class="form-control" v-model="form[fieldIndex]"
                                           :name="fieldIndex" required>
                                    <span v-if="errors[fieldIndex]" class="help-block">
                                    <strong> {{errors[fieldIndex].join() }} </strong>
                                    </span>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--<bootstrap-modal id="modal-register-success">-->
            <!--<div slot="header">User registered successfully</div>-->
            <!--<div slot="content">-->
                <!--<p>You will be redirected to login page after closing this window.</p>-->
            <!--</div>-->
        <!--</bootstrap-modal>-->
    </div>
</template>


<script type="text/ecmascript-6">
    import auth from '../../auth'
    import flash from '../../flash'

    var defaultFormData = {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
    };

    export default {
        data() {
            return {
                fields: {
                    name: 'Name',
                    email: 'E-mail',
                    password: 'Password',
                    password_confirmation: 'Confirm Password'
                },
                form: defaultFormData,
                errors: {},
                message: ''
            }
        },

        mounted() {
            this.prepareComponent();
        },
        methods: {
            prepareComponent() {
            },
            register()
            {
                this.$http.post('/api/register', this.form).then(function (response) {
//                    console.log(response);
                    if (_.get(response, 'body.success') == true) {
                        this.fields.name = '';
                        this.fields.email = '';
                        this.fields.password = '';
                        this.fields.password_confirmation = '';
                        flash.setMessage('User registered successfully. You can now log in.');
                        this.$router.push('/login');
                    }
                }).catch(function (response) {
                    if (_.get(response, 'body.success') == false) {
                        this.errors = response.body.message;
                    }
                });
            }
        }
    }
</script>
