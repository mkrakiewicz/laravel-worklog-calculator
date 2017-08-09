<template>
    <div id="caloriesSettings">
        <h2>Calories Settings</h2>
        <p v-if="acl.crud_all_user_settings">All users settings.</p>
        <p v-else>Here you can set the amount of Calories per day.</p>
        <table>
            <tr v-if="acl.crud_all_user_settings">
                <th>User e-mail</th>
                <th>Value</th>
            </tr>
            <tr v-for="caloriesSetting in caloriesSettings">
                <td v-if="acl.crud_all_user_settings">
                    {{caloriesSetting.email}}
                </td>
                <td>
                    <input class="form-control" type="text" v-model="caloriesSetting.value"/>
                </td>
                <td>
                    <a @click="saveCaloriesSetting(caloriesSetting)"
                       class="btn btn-primary"> <i class="fa fa-save"></i> Save</a>
                </td>
                <td>
                    <p v-if="errors[caloriesSetting.id]">{{errors[caloriesSetting.id].join()}}</p>
                </td>
            </tr>
        </table>
    </div>
</template>
<script type="text/ecmascript-6">

    import auth from '../auth';
    import flash from '../flash';


    var defaultCaloriesSettingData = {
        name: '', email: '', password: '', password_confirmation: ''
    };
    export default {
        data() {
            return {
                caloriesSettings: [],
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
                this.getCaloriesSettings();

            },


            getCaloriesSettings() {
                this.$http.get('/api/users_calories_settings/usersOverview')
                        .then(function (response) {
                            this.caloriesSettings = response.data.data;
                        });
            },

            saveCaloriesSetting(caloriesSetting, errors) {
                var method = 'post';
                var url = '/api/users_calories_settings';
                var id = _.get(caloriesSetting, 'id', "");
//                console.log("ID: " + id + ", caloriesSetting: " + caloriesSetting);
                if (!!id) {
                    method = 'put';
                    url += "/" + id;
                }

                this.$http[method](url, caloriesSetting)
                        .then(function (response) {
                            if (_.get(response, 'body.success') == true) {
                                this.getCaloriesSettings();
                                Vue.set(this.errors, caloriesSetting.id, false);
                                flash.setMessage('Setting saved.')
                            }
                        }).catch(function (response) {
                    if (_.get(response, 'body.success') == false) {
                        var messageArr = _.get(response, 'body.message[value]',false);
                        if (messageArr) {
                            Vue.set(this.errors, caloriesSetting.id, messageArr);
                        }
                    }
                });
            }
        }
    }
</script>