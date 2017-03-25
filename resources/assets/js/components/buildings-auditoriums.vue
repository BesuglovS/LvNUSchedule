<template>
    <div>
        <div class="col-xs-12 col-sm-6">
            <table id="dailyScheduleTable">
                <tr>
                    <td>
                        <button type="button"
                                id="showSchedule"
                                v-on:click="showBuilding();"
                                class="btn btn-primary">
                            Занятость корпуса
                        </button>
                    </td>
                </tr>
                <tr>
                    <td id="calendarId" class="text-align-center">
                        <input type="text"
                               id="scheduleDate"
                               v-on:click="showsimplemodal();"
                               v-model="dateformatted"
                               readonly>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="dropdown dropdown-menu-right" id="buildingSelector">
                            <button class="btn btn-default dropdown-toggle" type="button" id="teacher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span v-if="selectedBuilding == null">Выберите корпус:</span>
                                <span v-if="selectedBuilding !== null">{{selectedBuilding.name}}</span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="teacher1">
                                <li v-for="building in buildingsList"
                                    v-on:click.prevent="buildingChosen(building);">
                                    <a href="#">{{building.name}}</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
        </div>


        <building-auditoriums-modal v-if="showModalResult"
                                :selectedBuilding="selectedBuilding"
                                :dateformatted="dateformatted"
                                :schedule="schedule"
                                @close="showModalResult = false">
        </building-auditoriums-modal>

        <modal v-if="showSimpleModal"
               @close="showSimpleModal = false">
            <h3>Дату можно выбрать слева</h3>
        </modal>

    </div>
</template>

<script>
    var moment = require('moment');

    export default {
        computed: {
            dateformatted: function () {
                let dt = moment(this.datepickerDate);
                return dt.format('DD MMMM YYYY г.');
            }
        },
        props: ['buildingsList', 'datepickerDate'],
        data: function () {
            return {
                schedule: null,
                selectedBuilding: null,
                showModalResult: false,
                showSimpleModal: false
            }
        },
        components: {
            "building-auditoriums-modal": require('./buildings-auditoriums-modal.vue'),
            "modal": require('./modal.vue')
        },
        methods: {
            buildingChosen (building) {
                this.selectedBuilding = building;
            },
            showBuilding() {
                axios.get('./api/api?action=dailyBuildingSchedule' +
                    '&date=' + moment(this.datepickerDate).format('YYYY-MM-DD') +
                    '&buildingId=' + this.selectedBuilding.id)
                    .then(response => {
                            this.schedule = response.data;
                            this.showModalResult = true;
                        }
                    );
            },
            showsimplemodal() {
                this.showSimpleModal = true;
            }
        }
    }
</script>