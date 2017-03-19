
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
Vue.component('daily-schedule', require('./components/daily-schedule.vue'));
Vue.component('daily-schedule-modal', require('./components/daily-schedule-modal.vue'));
Vue.component('teacher-schedule', require('./components/teacher-schedule.vue'));
Vue.component('teacher-schedule-modal', require('./components/teacher-schedule-modal.vue'));
Vue.component('building-auditoriums', require('./components/buildings-auditoriums.vue'));
Vue.component('building-auditoriums-modal', require('./components/buildings-auditoriums-modal.vue'));
Vue.component('building-auditoriums-modal', require('./components/buildings-auditoriums-modal.vue'));
Vue.component('date-picker', require('./components/date-picker.vue'));
Vue.component('modal', require('./components/modal.vue'));

const app = new Vue({
    el: '#app',
    created: function() {
        this.datepickerDate = new Date();
        axios.get('/api/api?action=mainPageData')
            .then(response => {
                this.weekNumber = response.data.currentWeek;
                this.mainGroups = response.data.mainGroups;
                this.teacherList= response.data.teacherList;
                this.buildingsList= response.data.buildingsList;
            }
        );
    },
    data: function () {
        return {
            datepickerDate: null,
            weekNumber: '',
            mainGroups: null,
            teacherList: null,
            buildingsList: null
        }
    },
    template: `
    <div> 
        <div class="container">
            <div class="panel panel-default">
                <div class="row vertical-align">
                    <header-section :weekNumber="weekNumber"></header-section>
                </div>
            </div>
        </div>   
        
        <div class="container">
            <div class="panel panel-default">
                <div class="row">
                    <date-picker @dateChanged="newDate" :datepickerDate="datepickerDate"></date-picker>                    
                    <daily-schedule :datepickerDate="datepickerDate" :mainGroups="mainGroups"></daily-schedule>                    
                    <teacher-schedule :teacherList="teacherList"></teacher-schedule>
                    <building-auditoriums :datepickerDate="datepickerDate" :buildingsList="buildingsList"></building-auditoriums>
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
