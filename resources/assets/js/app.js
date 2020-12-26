
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');
window.Video = require('video.js');
require('./custom');
window.Vue = require('vue');
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(require('vue-moment'));
Vue.use(require('vue-session'));
Vue.use(require('vue-resource'));
Vue.component('modules', require('./components/admin/Modules.vue'));
Vue.component('students', require('./components/admin/Users.vue'));
// Vue.component('currentchapter', require('./components/user/CurrentChapter.vue')); 
// Vue.component('currentprogress', require('./components/user/CurrentProgress.vue'));
// Vue.component('nextchapters', require('./components/user/NextChapters.vue'));
// Vue.component('videoplayer', require('./components/user/VideoPlayer.vue'));
// Vue.component('test', require('./components/user/Test.vue'));
// Vue.component('syllabus', require('./components/user/Syllabus.vue'));
Vue.component('changepassword', require('./components/changepassword.vue'));
Vue.component('notification', require('./components/notification.vue'));
Vue.component('test-result-chart', require('./components/User/testResultChart/index.vue'));
Vue.component('earning-chart', require('./components/admin/earning-graph/index.vue'));
Vue.component('admin-board',require('./components/admin/AdminBoard/index.vue'));
Vue.component('admin-approval', require('./components/admin/admin-approval/index.vue'));
Vue.component('recent-activities', require('./components/admin/recent-activities/index.vue'));
Vue.component('earning-graph', require('./components/admin/earning-graph/index.vue'));
Vue.component('age-wise-graph', require('./components/admin/AgeWiseGraph/index.vue'));

const app = new Vue({
    el: '#app'
});
