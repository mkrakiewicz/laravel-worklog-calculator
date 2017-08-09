/* globals localStorage */
// import $http from 'vue-resource';
import Vue from 'vue';
import VueResource from 'vue-resource';
Vue.use(VueResource);

export default {

    getworklogs(df, dt, tf, tt, limit, offset, cb) {
        var url = '/api/worklogs?with=user&withExceededWorklogs=1&orderBy=time&sortedBy=desc';
        // var df = _.get(this, 'search.date.from', false);
        // var dt = _.get(this, 'search.date.to', false);
        // var tf = _.get(this, 'search.time.from', false);
        // var tt = _.get(this, 'search.time.to', false);
        if (limit) {
            url += '&limit=' + limit;
        }
        if (offset) {
            url += '&offset=' + offset;
        }

        if (df) {
            url += '&dateFrom=' + df;
        }
        if (dt) {
            url += '&dateTo=' + dt;
        }
        if (tf) {
            url += '&timeFrom=' + tf;
        }
        if (tt) {
            url += '&timeTo=' + tt;
        }

        Vue.http.get(url).then(cb);
    }
}


