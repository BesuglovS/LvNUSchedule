
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example', require('./components/Example.vue'));
Vue.component('header-section', require('./components/header-section.vue'));
Vue.component('daily-schedule', require('./components/daily-schedule.vue'));
Vue.component('daily-schedule-modal', require('./components/daily-schedule-modal.vue'));
Vue.component('teacher-schedule', require('./components/teacher-schedule.vue'));
Vue.component('teacher-schedule-modal', require('./components/teacher-schedule-modal.vue'));

const app = new Vue({
    el: '#app',
    created: function() {
        axios.get('/api/api?action=mainPageData')
            .then(response => {
                this.weekNumber = response.data.currentWeek;
                this.mainGroups = response.data.mainGroups;
                this.teacherList= response.data.teacherList;
            }
        );
    },
    data: function () {
        return {
            weekNumber: '',
            mainGroups: null,
            teacherList: null
        }
    },
    template: `
    <div> 
        <header-section :weekNumber="weekNumber"></header-section>
        <div class="container">
            <div class="panel panel-default">
                <div class="row">
                    <daily-schedule :mainGroups="mainGroups"></daily-schedule>                    
                    <teacher-schedule :teacherList="teacherList"></teacher-schedule>
                </div>
            </div>
        </div>
    </div>`
});
