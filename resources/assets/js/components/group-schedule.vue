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
                            Расписание группы
                        </button>
                    </td>
                </tr>
                <tr>
                    <td id="calendarId" >
                        <input type="text"
                               id="scheduleDate"
                               v-on:click="showsimplemodal();"
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
                        <button type="button"
                                id="showGroupDisciplines"
                                v-on:click="showGroupDisciplines();"
                                class="btn btn-primary">
                            Дисциплины группы
                        </button>
                    </td>
                </tr>
            </table>
        </div>


        <daily-schedule-modal v-if="showModalResult"
               :lessons="lessons"
               :selectedGroup="selectedGroup"
               :dateformatted="dateformatted"
               @close="showModalResult = false">
        </daily-schedule-modal>

        <modal v-if="showSimpleModal"
               @close="showSimpleModal = false">
            <h3>Дату можно выбрать слева</h3>
        </modal>

        <student-group-disciplines-modal v-if="showDisciplinesModal"
                              :selectedGroup="selectedGroup"
                              :groupDisciplines="groupDisciplines"
                              @close="showDisciplinesModal = false">
        </student-group-disciplines-modal>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    var moment = require('moment');
    moment.locale('ru');

    export default {
        props: ['mainGroups', 'datepickerDate'],
        computed: {
            dateformatted: function () {
                var dt = moment(this.datepickerDate);
                return dt.format('DD MMMM YYYY г.');
            }
        },
        data: function () {
            return {
                lessons: null,
                selectedGroup: null,
                groupDisciplines: null,
                showModalResult: false,
                showSimpleModal: false,
                showDisciplinesModal: false
            }
        },
        components: {
            Datepicker,
            "daily-schedule-modal": require('./daily-schedule-modal.vue'),
            "modal": require('./modal.vue'),
            "student-group-disciplines-modal": require('./student-group-disciplines-modal.vue'),
        },
        methods: {
            groupChosen (group) {
                this.selectedGroup = group;
            },
            showSchedule() {
                var dt = moment(this.datepickerDate);
                var dateString = dt.format('YYYY-MM-DD');
                axios.get('./api/api?action=dailySchedule&groupId=' +
                    this.selectedGroup.id + '&date=' + dateString)
                    .then(response => {
                        this.lessons = response.data;
                        this.showModalResult = true;
                    }
                );
            },
            showsimplemodal() {
                this.showSimpleModal = true;
            },
            showGroupDisciplines() {
                axios.get('./api/api?action=groupDisciplines&groupId=' +
                    this.selectedGroup.id)
                    .then(response => {
                        let data = response.data;
                        let otchetnost = ["-", "Зачёт",  "Экзамен" ,"Зачёт + Экзамен" , "Зачёт с оценкой"];

                        for(let i = 0; i < data.length; i++) {
                            data[i].attestation = otchetnost[data[i].attestation];
                        }

                        this.groupDisciplines = data;

                        this.showDisciplinesModal = true;
                    });
            }
        }
    }
</script>