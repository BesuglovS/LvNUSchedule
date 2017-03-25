<template>
    <div>
        <div class="col-xs-12 col-sm-6">
            <table id="dailyScheduleTable">
                <tr>
                    <td>
                        <button type="button"
                                id="showTeacherSchedule"
                                v-on:click="showSchedule();"
                                class="btn btn-primary">
                            Расписание
                        </button>
                    </td>
                    <td>
                        <button type="button"
                                id="showTeacherDisciplines"
                                v-on:click="showDisciplines();"
                                class="btn btn-primary">
                            Дисциплины
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" id="teacherSelectorTd">
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

        <teacher-disciplines-modal v-if="showModalDisciplinesResult"
                                :selectedTeacher="selectedTeacher"
                                :disciplinesList="disciplinesList"
                                @close="showModalDisciplinesResult = false">
        </teacher-disciplines-modal>


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
                showModalResult: false,
                showModalDisciplinesResult: false,
                disciplinesList: null
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
                axios.get('./api/api?action=teacherSchedule&teacherId=' +
                    this.selectedTeacher.id)
                    .then(response => {
                            this.schedule = response.data;
                            this.showModalResult = true;
                        }
                    );
            },
            showDisciplines() {
                axios.get('./api/api?action=teacherDisciplines&teacherId=' +
                    this.selectedTeacher.id)
                    .then(response => {
                        let data = response.data;
                        let otchetnost = ["-", "Зачёт",  "Экзамен" ,"Зачёт + Экзамен" , "Зачёт с оценкой"];

                        for(let i = 0; i < data.disciplines.length; i++) {
                            data.disciplines[i].attestation = otchetnost[data.disciplines[i].attestation];
                        }

                        this.disciplinesList = data;

                        this.showModalDisciplinesResult = true;
                    });
            }
        }
    }
</script>