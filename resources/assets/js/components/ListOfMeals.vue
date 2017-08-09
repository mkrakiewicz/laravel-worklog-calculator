<template>
    <div id="mealList">
        <h2>List of meals</h2>
        <p>Here you can add a meal, or view a list of your meals below.</p>
        <a class="btn btn-primary btn-lg pull-right" @click="showAddMealModal"><i class="fa fa-plus"></i> Add</a>
        <div id="search">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Date range (from - to)</label>
                        </div>
                        <div class="col-md-6">
                            <input id="searchDatePeriodFrom" v-model="search.date.from" type="text" class="form-control"
                                   placeholder="2017-07-05">
                        </div>
                        <div class="col-md-6">
                            <input id="searchDatePeriodTo" v-model="search.date.to" type="text" class="form-control"
                                   placeholder="2017-08-06">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="col-md-12">
                        <label>Time range (from - to)</label>
                    </div>
                    <div class="col-md-6">
                        <input id="searchTimeFrom" v-model="search.time.from" type="text" class="form-control"
                               placeholder="12:00">
                    </div>
                    <div class="col-md-6">
                        <input id="searchTimeTo" v-model="search.time.to" type="text" class="form-control"
                               placeholder="15:45">
                    </div>
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <input @click="getMeals()" class="btn btn-primary" type="button" value="Filter">
                </div>
                <div class="col-md-1">
                    <label>&nbsp;</label>
                    <input @click="resetSearch()" class="btn btn-default" type="button" value="Clear">
                </div>
            </div>
        </div>
        <table class="table table-borderless m-b-none">
            <thead>
            <tr>
                <th v-if="acl.crud_all_meals">User e-mail</th>
                <th>Meal</th>
                <th>Calories (kcal)</th>
                <th>Time</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="meal in meals" v-bind:class="{exceededCalories : meal.exceeded_calories}" class="meal">
                <td v-if="acl.crud_all_meals">
                    {{ meal.user.email }}
                </td>
                <!-- Client Name -->
                <td style="vertical-align: middle;">
                    {{ meal.name }}
                </td>
                <td style="vertical-align: middle;">
                    {{ meal.calories }}
                </td>
                <td style="vertical-align: middle;">
                    {{ meal.time }}
                </td>
                <!-- Revoke Button -->
                <td style="vertical-align: middle;">
                    <div class="btn-group">
                        <a @click="showEditMealModal(meal)"
                           class="btn btn-default"><i
                                class="fa fa-edit"></i> Edit</a>
                        <a @click="showDeleteMealModal(meal)"
                           class="btn btn-danger"><i
                                class="fa fa-remove"></i> Delete</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div id="pagination">
            <button v-if="pagination.offsetFactor > 0" @click="getMeals(-1)" type="button" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> Previous page
            </button>
            <button v-if="meals.length > 0" @click="getMeals(1)" type="button" class="btn btn-primary pull-right">
                Next page <i class="fa fa-arrow-right"></i></button>
        </div>

        <bootstrap-modal id="modal-edit-meal">
            <div slot="header"><span v-if="modal.meal.id">Edit</span><span v-else>Add</span> meal</div>
            <div slot="content">
                <form class="form-horizontal" role="form">

                    <div v-for="(fieldName, fieldIndex) in modal.fields" class="form-group">
                        <label :for="fieldIndex" class="col-md-3 control-label">{{fieldName}}</label>
                        <div class="col-md-7">
                            <input type="text"
                                   class="form-control" v-model="modal.meal[fieldIndex]"
                                   :placeholder="fieldIndex == 'time' ? '2017-06-23 12:45:00' :''"
                                   :name="fieldIndex" required>
                                    <span v-if="modal.errors[fieldIndex]" class="help-block">
                                    <strong> {{modal.errors[fieldIndex].join() }} </strong>
                                    </span>
                        </div>
                    </div>
                    <div v-if="acl.crud_all_meals" class="form-group">
                        <label class="col-md-3 control-label">User</label>
                        <div class="col-md-7">

                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="userDropdown"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Choose
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li v-for="user in users"><a href="#" @click.prevent="selectUser(user)">{{user.email}}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </form>
            </div>
            <div slot="footer">
                <button @click="handleEditMealModalSave" type="button" class="btn btn-primary">
                    Save
                </button>
                <button id="editModalCloseButton" type="button" class="btn btn-default" data-dismiss="modal">Close
                </button>
            </div>

        </bootstrap-modal>


        <bootstrap-modal id="modal-delete-meal">
            <div slot="header">Delete meal</div>
            <div slot="content">
                <p>Are you sure?</p>
            </div>
            <div slot="footer">
                <button @click="handleMealModalDelete" type="button" class="btn btn-danger" data-dismiss="modal">
                    Delete
                </button>
                <button id="deleteModalCloseButton" type="button" class="btn btn-default" data-dismiss="modal">Close
                </button>
            </div>

        </bootstrap-modal>


    </div>
</template>

<script type="text/ecmascript-6">
    import auth from '../auth.js';
    import mealApi from '../meal-api.js';
    import daterangepicker from 'bootstrap-daterangepicker';
    import flash from '../flash';

    var defaultMealData = {
        name: '', calories: '', time: '', user: {id: '', email: ''}
    };

    var defaultSearchData = {
        time: {from: '', to: ''},
        date: {from: '', to: ''}
    };

    export default {
        data() {
            return {
                meals: [],
                modal: {
                    meal: defaultMealData,
                    fields: {
                        name: 'Name',
                        calories: 'Calories (kcal)',
                        time: 'Time',
                    },
                    errors: []
                },
                search: defaultSearchData,
                acl: {},
                users: {},
                pagination: {
                    limit: 15,
                    offsetFactor: 0
                }
            };
        },

        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {


                this.getMeals();
                this.getUsers()
                this.acl = auth.acl();
                var that = this;
                var originalDatePeriod = '';
                var originalTimePeriod = '';
                var datePeriodInputFrom = $('#searchDatePeriodFrom');
                var datePeriodInputTo = $('#searchDatePeriodTo');
                var timePeriodInputFrom = $('#searchTimeFrom');
                var timePeriodInputTo = $('#searchTimeTo');

                var allInputs = datePeriodInputFrom.add(datePeriodInputTo);
                allInputs = allInputs.add(timePeriodInputFrom);
                allInputs = allInputs.add(timePeriodInputTo);
                allInputs.daterangepicker({
                    "autoApply": true,
                    "timePicker": true,
                    "timePicker24Hour": true,
                    "autoUpdateInput": false,
                    "startDate": "07/21/2017",
                    "endDate": "07/27/2017"
                });
                allInputs.on('apply.daterangepicker', function (ev, picker) {
                    that.search.date.from = picker.startDate.format('YYYY-MM-DD');
                    that.search.date.to = picker.endDate.format('YYYY-MM-DD');
                    that.search.time.from = picker.startDate.format('HH:mm');
                    that.search.time.to = picker.endDate.format('HH:mm');
                });
                allInputs.on('cancel.daterangepicker', function (ev, picker) {

                });


                var $modalTimeInput = $('#modal-edit-meal').find('input[name=time]');
                $modalTimeInput.daterangepicker({
                    "autoApply": true,
                    "timePicker": true,
                    "timePicker24Hour": true,
                    "singleDatePicker": true,
                    "autoUpdateInput": false,
                    "startDate": "07/21/2017",
                    "endDate": "07/27/2017"
                });

                $modalTimeInput.on('apply.daterangepicker', function (ev, picker) {
                    that.modal.meal.time = picker.startDate.format('YYYY-MM-DD HH:mm:ss');
                });
            },


            getMeals(offsetIncrement) {
                var df = _.get(this, 'search.date.from', false);
                var dt = _.get(this, 'search.date.to', false);
                var tf = _.get(this, 'search.time.from', false);
                var tt = _.get(this, 'search.time.to', false);
                var that = this;
                if (!!offsetIncrement) {
                    that.pagination.offsetFactor += offsetIncrement;
                } else {
                    that.pagination.offsetFactor = 0;
                }
                mealApi.getMeals(df, dt, tf, tt, that.pagination.limit, that.pagination.offsetFactor * that.pagination.limit, function (response) {
                    that.meals = response.data.data;
                })
            },

            showAddMealModal()
            {
                this.modal.meal = defaultMealData;
                $('#modal-edit-meal').modal('show');
            },
            /**
             * Show the form for creating new clients.
             */
            showEditMealModal(meal) {
                this.modal.errors = [];
                this.modal.meal = meal;
                $('#modal-edit-meal').modal('show');
            },

            showDeleteMealModal(meal) {
                this.modal.meal = meal;
                $('#modal-delete-meal').modal('show');
            },
            handleEditMealModalSave() {
                var method = 'post';
                var url = '/api/meals';
                var id = _.get(this, 'modal.meal.id', "");
                if (id != "") {
                    method = 'put';
                    url += "/" + id;
                }

                var meal = _.get(this, 'modal.meal');
                meal.user_id = _.get(meal, 'user.id');
                delete meal['user'];
                this.$http[method](url, meal)
                        .then(function (response) {
                            if (_.get(response, 'body.success') == true) {
                                this.getMeals();
                                flash.setMessage('Meal saved.');
                                $('#editModalCloseButton').click();
                                this.modal.meal.name = '';
                                this.modal.meal.calories = '';
                                this.modal.meal.time = '';
                                this.modal.meal.user = '';
                                this.modal.meal.user_id = '';
                                this.modal.errors = [];
                            }
                        }).catch(function (response) {
                    if (_.get(response, 'body.success') == false) {
                        this.modal.errors = response.body.message;
                    }
                });
            },

            handleMealModalDelete() {
                var id = _.get(this, 'modal.meal.id');
                this.$http.delete('api/meals/' + id)
                        .then(function (response) {
                            if (_.get(response, 'body.success') == true) {
                                this.getMeals();
                                flash.setMessage('Meal deleted.');
                                $('#deleteModalCloseButton').click();
                            }
                        }).catch(function (response) {
                    if (_.get(response, 'body.success') == false) {
                        this.modal.errors = response.body.message;
                        alert("Problem deleting meals.");
                    }
                });
            },
            getUsers() {
                this.$http.get('/api/users')
                        .then(function (response) {
                            this.users = response.data.data;
                        }).catch(function (response) {

                });
            },
            selectUser(user)
            {
                this.modal.meal.user = user;
                $('#userDropdown').text(user.email);
            },
            resetSearch () {
                this.search.date.from = '';
                this.search.date.to = '';
                this.search.time.from = '';
                this.search.time.to = '';
                this.getMeals();
            }
        }
    }
</script>
