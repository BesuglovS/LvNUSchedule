
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

Vue.component('header-section', require('./components/header-section.vue'));
Vue.component('group-schedule', require('./components/group-schedule.vue'));
Vue.component('daily-schedule-modal', require('./components/daily-schedule-modal.vue'));
Vue.component('teacher-schedule', require('./components/teacher-schedule.vue'));
Vue.component('teacher-schedule-modal', require('./components/teacher-schedule-modal.vue'));
Vue.component('building-auditoriums', require('./components/buildings-auditoriums.vue'));
Vue.component('building-auditoriums-modal', require('./components/buildings-auditoriums-modal.vue'));
Vue.component('building-auditoriums-modal', require('./components/buildings-auditoriums-modal.vue'));
Vue.component('date-picker', require('./components/date-picker.vue'));
Vue.component('modal', require('./components/modal.vue'));
Vue.component('teacher-disciplines-modal', require('./components/teacher-disciplines-modal.vue'));
Vue.component('student-group-disciplines-modal', require('./components/student-group-disciplines-modal.vue'));
Vue.component('group-session-schedule', require('./components/group-session-schedule.vue'));
Vue.component('group-session-schedule-modal', require('./components/group-session-schedule-modal.vue'));


const app = new Vue({
    el: '#app',
    created: function() {
        this.datepickerDate = new Date();
        axios.get('./api/api?action=mainPageData')
            .then(response => {
                this.weekNumber = response.data.currentWeek;
                this.mainGroups = response.data.mainGroups;
                this.teacherList = response.data.teacherList;
                this.buildingsList = response.data.buildingsList;
                this.happy = response.data.happy;
            }
        );
    },
    data: function () {
        return {
            datepickerDate: null,
            weekNumber: '',
            mainGroups: null,
            teacherList: null,
            buildingsList: null,
            happy: null
        }
    },
    template: `
    <div> 
        <header-section :weekNumber="weekNumber"></header-section>       
        
        <div class="container">
            <div class="panel panel-default">
                <div class="row">
                    <date-picker @dateChanged="newDate" :datepickerDate="datepickerDate" :happy="happy"></date-picker>
                    <group-schedule :datepickerDate="datepickerDate" :mainGroups="mainGroups"></group-schedule>                    
                    <teacher-schedule :teacherList="teacherList"></teacher-schedule>
                    <building-auditoriums :datepickerDate="datepickerDate" :buildingsList="buildingsList"></building-auditoriums>
                    <group-session-schedule :mainGroups="mainGroups"></group-session-schedule>
                </div>
            </div>    
        </div>
    </div>
`,
    methods: {
        newDate(dt) {
           this.datepickerDate = dt;
        }
    }
});
