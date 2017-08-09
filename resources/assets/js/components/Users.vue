<template>
    <div>
        <h2>List of users</h2>
        <p>Here you can view a list of users.</p>
        <a class="btn btn-primary" @click="showAddUserModal"><i class="fa fa-plus"></i> Add</a>
        <table class="table table-borderless m-b-none">
            <thead>
            <tr>
                <th>User name</th>
                <th>E-mail</th>
                <th>Created At</th>

                <th></th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="user in users">
                <td style="vertical-align: middle;">
                    {{ user.name }}
                </td>
                <td style="vertical-align: middle;">
                    {{ user.email }}
                </td>
                <td style="vertical-align: middle;">
                    {{ user.created_at }}
                </td>
                <td style="vertical-align: middle;">
                    <div class="btn-group">
                        <a @click="showEditUserModal(user)"
                           class="btn btn-default"><i
                                class="fa fa-edit"></i> Edit</a>
                        <a @click="showDeleteUserModal(user)"
                           class="btn btn-danger"><i
                                class="fa fa-remove"></i> Delete</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>


        <bootstrap-modal id="modal-edit-user">
            <div slot="header">Edit User</div>
            <div slot="content">
                <form class="form-horizontal" role="form">

                    <div v-for="(fieldName, fieldIndex) in modal.fields" class="form-group">
                        <label :for="fieldIndex" class="col-md-3 control-label">{{fieldName}}</label>

                        <div class="col-md-7">
                            <input v-if="fieldIndex.indexOf('password') === -1" type="text"
                                   class="form-control" v-model="modal.user[fieldIndex]"
                                   :name="fieldIndex" required>
                            <input v-else type="password"
                                   class="form-control" v-model="modal.user[fieldIndex]"
                                   :name="fieldIndex" required>
                                    <span v-if="modal.errors[fieldIndex]" class="help-block">
                                    <strong> {{modal.errors[fieldIndex].join() }} </strong>
                                    </span>
                        </div>
                    </div>

                </form>
            </div>
            <div slot="footer">
                <button @click="handleEditUserModalSave" type="button" class="btn btn-primary">
                    Save
                </button>
                <button id="editModalCloseButton" type="button" class="btn btn-default" data-dismiss="modal">Close
                </button>
            </div>

        </bootstrap-modal>


        <bootstrap-modal id="modal-delete-user">
            <div slot="header">Delete user</div>
            <div slot="content">
                <p>Are you sure?</p>
            </div>
            <div slot="footer">
                <button @click="handleUserModalDelete" type="button" class="btn btn-danger" data-dismiss="modal">
                    Delete
                </button>
                <button id="deleteModalCloseButton" type="button" class="btn btn-default" data-dismiss="modal">Close
                </button>
            </div>

        </bootstrap-modal>


    </div>
</template>

<script type="text/ecmascript-6">
    import flash from '../flash';

    var defaultUserData = {
        name: '', email: '', password: '', password_confirmation: ''
    };
    export default {
        data() {
            return {
                users: [],
                modal: {
                    user: defaultUserData,
                    fields: {
//                        id: 'Id',
                        name: 'Name',
                        email: 'E-mail',
                        password: 'Password',
                        password_confirmation: 'Confirm Password'
                    },
                    errors: []
                }
            };
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {

                this.getUsers();
            },

            /**
             * Get all of the authorized tokens for the user.
             */
            getUsers() {
                this.$http.get('/api/users')
                        .then(function (response) {
                            this.users = response.data.data;
                        });
            },

            showAddUserModal()
            {
                this.modal.user = defaultUserData;
                $('#modal-edit-user').modal('show');
            },
            /**
             * Show the form for creating new clients.
             */
            showEditUserModal(user) {
                this.modal.errors = [];
                this.modal.user = user;
                $('#modal-edit-user').modal('show');
            },

            showDeleteUserModal(user) {
                this.modal.user = user;
                $('#modal-delete-user').modal('show');
            },
            handleEditUserModalSave() {
                var method = 'post';
                var url = '/api/users';
                var id = _.get(this, 'modal.user.id', "");
//                console.log("ID: " + id);
                if (id != "") {
                    method = 'put';
                    url += "/" + id;
                }

                this.$http[method](url, _.get(this, 'modal.user'))
                        .then(function (response) {
                            if (_.get(response, 'body.success') == true) {
                                this.getUsers();
                                flash.setMessage('User saved.');
                                $('#editModalCloseButton').click();

                                this.modal.user.name = '';
                                this.modal.user.email = '';
                                this.modal.user.password = '';
                                this.modal.user.password_confirmation = '';
                            }
                        }).catch(function (response) {
                    if (_.get(response, 'body.success') == false) {
                        this.modal.errors = response.body.message;
                    }
                });
            },

            handleUserModalDelete() {
                var id = _.get(this, 'modal.user.id');
                this.$http.delete('api/users/' + id)
                        .then(function (response) {
                            if (_.get(response, 'body.success') == true) {
                                this.getUsers();
                                flash.setMessage('User deleted.');
                                $('#deleteModalCloseButton').click();
                            }
                        }).catch(function (response) {
                    if (_.get(response, 'body.success') == false) {
                        this.modal.errors = response.body.message;
                        alert("Problem deleting users.");
                    }
                });
            }
        }
    }
</script>
