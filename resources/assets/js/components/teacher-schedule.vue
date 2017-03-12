<template>
    <div>
        <div class="col-xs-12 col-sm-6">
            <table id="dailyScheduleTable">
                <tr>
                    <td>
                        <button type="button"
                                id="showSchedule"
                                v-on:click="showSchedule();"
                                class="btn btn-primary">
                            Показать расписание
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dropdown dropdown-menu-right" id="teacherSelector">
                            <button class="btn btn-default dropdown-toggle" type="button" id="teacher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span v-if="selectedTeacher == null">Выберите преподавателя:</span>
                                <span v-if="selectedTeacher !== null">{{selectedTeacher.fio}}</span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="teacher1">
                                <li v-for="teacher in teacherList"
                                    v-on:click.prevent="teacherChosen(teacher);">
                                    <a href="#">{{teacher.fio}}</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
        </div>


        <teacher-schedule-modal v-if="showModalResult"
                                :selectedTeacher="selectedTeacher"
                                :schedule="schedule"
                                @close="showModalResult = false">
        </teacher-schedule-modal>


    </div>
</template>

<script>
    export default {
        mounted() {
        },
        props: ['teacherList'],
        computed: {
        },
        data: function () {
            return {
                schedule: null,
                selectedTeacher: null,
                showModalResult: false
            }
        },
        components: {
            "teacher-schedule-modal": require('./teacher-schedule-modal.vue')
        },
        methods: {
            teacherChosen (teacher) {
                this.selectedTeacher = teacher;
            },
            showSchedule() {
                axios.get('/api/api?action=teacherSchedule&teacherId=' +
                    this.selectedTeacher.id)
                    .then(response => {
                            this.schedule = response.data;
                            this.showModalResult = true;
                        }
                    );
            }
        }
    }
</script>