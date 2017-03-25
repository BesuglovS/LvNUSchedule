<template>
    <div>
        <div class="col-xs-12 col-sm-6">
            <table id="dailyScheduleTable">
                <tr>
                    <td >
                        <button type="button"
                                id="showSchedule"
                                v-on:click="showSchedule();"
                                class="btn btn-primary">
                            Сессия группы
                        </button>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">
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
            </table>
        </div>


        <group-session-schedule-modal v-if="showModalResult"
                              :exams="exams"
                              :selectedGroup="selectedGroup"
                              @close="showModalResult = false">
        </group-session-schedule-modal>
    </div>
</template>

<script>
    export default {
        props: ['mainGroups'],
        data: function () {
            return {
                exams: null,
                selectedGroup: null,
                showModalResult: false
            }
        },
        components: {
            "group-session-schedule-modal": require('./group-session-schedule-modal.vue')
        },
        methods: {
            groupChosen (group) {
                this.selectedGroup = group;
            },
            showSchedule() {
                axios.get('./api/api?action=groupExams&groupId=' +
                    this.selectedGroup.id)
                    .then(response => {
                        for(let i = 0; i < response.data.length; i++) {
                            console.log('start');
                            let csplit = response.data[i].consultation_datetime.split(' ');
                            let esplit = response.data[i].exam_datetime.split(' ');
                            if (csplit.length > 1) {
                                response.data[i].cons_date = csplit[0];
                                response.data[i].cons_time = csplit[1];
                            }

                            if (esplit.length > 1) {
                                response.data[i].exam_date = esplit[0];
                                response.data[i].exam_time = esplit[1];
                            }
                        }
                        this.exams = response.data;
                        this.showModalResult = true;
                    });

            }
        }
    }
</script>