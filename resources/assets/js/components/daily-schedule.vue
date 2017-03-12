<template>
    <div>
        <div class="col-xs-12 col-sm-6">
            <table id="dailyScheduleTable">
                <tr>
                    <td colspan="2">
                        <button type="button"
                                id="showSchedule"
                                v-on:click="showSchedule();"
                                class="btn btn-primary">
                            Показать расписание
                        </button>
                    </td>
                </tr>
                <tr>
                    <td id="calendarId">
                        <input type="text"
                               id="scheduleDate"
                               v-model="dateformatted"
                               readonly>
                    </td>
                    <td>
                        <div class="dropdown dropdown-menu-right" id="groupSelector">
                            <button class="btn btn-default dropdown-toggle" type="button" id="studentGroup" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span v-if="selectedGroup == null">Выберите группу:</span>
                                <span v-if="selectedGroup !== null">{{selectedGroup.name}}</span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="studentGroup1">
                                <li v-for="group in mainGroups"
                                    v-on:click.prevent="groupChosen(group);">
                                    <a href="#">{{group.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <datepicker language = "ru"
                                    id="dailydatepicker"
                                    :inline="true"
                                    :monday-first = "true"
                                    format = "dd.MM.yyyy"
                                    v-model = "date">
                        </datepicker>
                    </td>
                </tr>
            </table>
        </div>


        <daily-schedule-modal v-if="showModalResult"
               :lessons="lessons"
               :selectedGroup="selectedGroup"
               @close="showModalResult = false">
        </daily-schedule-modal>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    var moment = require('moment');
    moment.locale('ru');

    export default {
        mounted() {
            this.date = new Date();
        },
        props: ['mainGroups'],
        computed: {
            dateformatted: function () {
                var dt = moment(this.date);
                return dt.format('DD MMMM YYYY г.');
            }
        },
        data: function () {
            return {
                lessons: null,
                date: null,
                selectedGroup: null,
                showModalResult: false
            }
        },
        components: {
            Datepicker,
            "daily-schedule-modal": require('./daily-schedule-modal.vue')
        },
        methods: {
            groupChosen (group) {
                this.selectedGroup = group;
            },
            showSchedule() {
                var dt = moment(this.date);
                var dateString = dt.format('YYYY-MM-DD');
                axios.get('/api/api?action=dailySchedule&groupId=' +
                    this.selectedGroup.id + '&date=' + dateString)
                    .then(response => {
                        this.lessons = response.data;
                        this.showModalResult = true;
                    }
                );
            }
        }
    }
</script>