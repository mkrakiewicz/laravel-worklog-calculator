/* globals localStorage */
// import $http from 'vue-resource';
import Vue from 'vue';

export default {
    message : false,
    setMessage (message) {
        this.message = message;
        this.onChange();
    },

    getMessage () {
        return this.message;
    },
    clearWithoutEvent()
    {
        this.message = false;
    },
    onChange () {
    }
}


