<template>
    <div id="worklogsSettings">
        <h2>Worklogs Settings</h2>
        <p v-if="acl.crud_all_user_settings">All users settings.</p>
        <p v-else>Here you can set the amount of Worklogs per day.</p>
        <table>
            <tr v-if="acl.crud_all_user_settings">
                <th>User e-mail</th>
                <th>Value</th>
            </tr>
            <tr v-for="worklogsSetting in worklogsSettings">
                <td v-if="acl.crud_all_user_settings">
                    {{worklogsSetting.email}}
                </td>
                <td>
                    <input class="form-control" type="text" v-model="worklogsSetting.value"/>
                </td>
                <td>
                    <a @click="saveWorklogsSetting(worklogsSetting)"
                       class="btn btn-primary"> <i class="fa fa-save"></i> Save</a>
                </td>
                <td>
                    <p v-if="errors[worklogsSetting.id]">{{errors[worklogsSetting.id].join()}}</p>
                </td>
            </tr>
        </table>
    </div>
</template>
<script type="text/ecmascript-6">

    import auth from '../auth';
    import flash from '../flash';


    var defaultWorklogsSettingData = {
        name: '', email: '', password: '', password_confirmation: ''
    };
    export default {
        data() {
            return {
                worklogsSettings: [],
                errors: [],
                acl: {}
            };
        },


        mounted() {
            this.prepareComponent();
        },

        methods: {

            prepareComponent() {
                this.acl = auth.acl();
                this.getWorklogsSettings();

            },


            getWorklogsSettings() {
                this.$http.get('/api/users_worklogs_settings/usersOverview')
                        .then(function (response) {
                            this.worklogsSettings = response.data.data;
                        });
            },

            saveWorklogsSetting(worklogsSetting, errors) {
                var method = 'post';
                var url = '/api/users_worklogs_settings';
                var id = _.get(worklogsSetting, 'id', "");
//                console.log("ID: " + id + ", worklogsSetting: " + worklogsSetting);
                if (!!id) {
                    method = 'put';
                    url += "/" + id;
                }

                this.$http[method](url, worklogsSetting)
                        .then(function (response) {
                            if (_.get(response, 'body.success') == true) {
                                this.getWorklogsSettings();
                                Vue.set(this.errors, worklogsSetting.id, false);
                                flash.setMessage('Setting saved.')
                            }
                        }).catch(function (response) {
                    if (_.get(response, 'body.success') == false) {
                        var messageArr = _.get(response, 'body.message[value]',false);
                        if (messageArr) {
                            Vue.set(this.errors, worklogsSetting.id, messageArr);
                        }
                    }
                });
            }
        }
    }
</script>