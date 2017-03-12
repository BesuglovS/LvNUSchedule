<template>
    <div class="modal is-active">
        <div class="modal-background"></div>

        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{selectedTeacher.fio}}
                </p>
                <button class="delete" @click="$emit('close')"></button>
            </header>
            <section class="modal-card-body">
                <div v-if="isEmpty(schedule)">
                    <h2>Занятий нет</h2>
                </div>
                <div v-if="!isEmpty(schedule)">
                    <table id="modalTeacherSchedule" class="table is-bordered">
                        <template v-for="dowSchedule in schedule">
                            <tr>
                                <td colspan="2" class="modalTeacherScheduleDow">
                                    {{dowSchedule.dowString}}
                                </td>
                            </tr>
                            <template v-for="dowTimeSchedule in dowSchedule.dowLessons">
                                <tr>
                                    <td class="modalTeacherScheduleTime">
                                        {{dowTimeSchedule.time}}
                                    </td>
                                    <td>
                                        <table>
                                            <tr v-for="tfdDowTimeSchedule in orderByMinWeek(dowTimeSchedule.dowTimeLessons)">
                                                {{tfdDowTimeSchedule.lesson.group_name}}<br />
                                                {{tfdDowTimeSchedule.lesson.disc_name}}<br />
                                                ( {{tfdDowTimeSchedule.weeksString}} )<br />
                                                <template v-for="aud in tfdDowTimeSchedule.auditoriums">
                                                    {{aud}} <br />
                                                </template>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </table>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['schedule', 'selectedTeacher'],
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
            },
            orderByMinWeek(list) {
                let minWeekIndex = {};
                let minWeekArray = [];

                for (let key in list) {
                    if (list.hasOwnProperty(key)) {
                        minWeekIndex[list[key]['minWeek']] = key;
                        minWeekArray.push(list[key]['minWeek']);
                    }
                }

                minWeekArray.sort();

                let result = [];
                for (let key in minWeekIndex) {
                    if (minWeekIndex.hasOwnProperty(key)) {
                        result.push(list[minWeekIndex[key]]);
                    }
                }

                return result;
            }
        }
    }
</script>