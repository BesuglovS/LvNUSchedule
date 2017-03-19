<template>
    <div class="modal is-active">
        <div class="modal-background"></div>

        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{selectedBuilding.name}} ({{dateformatted}})
                </p>
                <button class="delete" @click="$emit('close')"></button>
            </header>
            <section class="modal-card-body">
                <div v-if="isEmpty(schedule.table)">
                    <h2>Занятий нет</h2>
                </div>
                <div v-if="!isEmpty(schedule.table)">
                    <table id="buildingAuditoriumsTable" class="table is-bordered centered">
                        <tr>
                            <td>Время</td>
                            <td v-for="aud in schedule.audArray">
                                {{aud}}
                            </td>
                        </tr>
                        <tr v-for="(timeData, ringTime) in schedule.table">

                            <td class="modalTeacherScheduleTime">
                                {{ringTime}}
                            </td>
                            <td v-for="aud in schedule.audArray"
                                v-bind:title="(schedule.table[ringTime][aud].length > 0) ? schedule.table[ringTime][aud][0].title: ''"
                            >
                                <template v-if="schedule.table[ringTime][aud].length > 0">
                                    <template v-for="e in schedule.table[ringTime][aud]">
                                      {{e.text}}
                                    </template>
                                </template>
                            </td>
                        </tr>
                    </table>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['schedule', 'selectedBuilding', 'dateformatted'],
        data: function () {
            return {
            }
        },
        methods: {
            isEmpty(obj) {
                // Does it have any properties of its own?
                // Note that this doesn't handle
                // toString and valueOf enumeration bugs in IE < 9
                for (var key in obj) {
                    if (hasOwnProperty.call(obj, key)) return false;
                }

                return true;
            }
        }
    }
</script>