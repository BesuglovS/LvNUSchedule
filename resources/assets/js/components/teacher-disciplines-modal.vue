<template>
    <div class="modal is-active">
        <div class="modal-background"></div>

        <div class="modal-card">api
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{selectedTeacher.fio}}
                </p>
                <button class="delete" @click="$emit('close')"></button>
            </header>
            <section class="modal-card-body">
                <div v-if="isEmpty(disciplinesList.disciplines)">
                    <h2>Дисциплин нет</h2>
                </div>
                <div v-if="!isEmpty(disciplinesList.disciplines)">
                    <table id="modalTeacherDiscipines" class="table is-bordered">
                        <tr>
                            <td>Дисциплина</td>
                            <td>Группа</td>
                            <td>Часов по плану</td>
                            <td>Часов в расписании</td>
                            <td v-for="month in disciplinesList.month_list">
                                <span v-if="month < 10">0</span>{{ month }}
                            </td>
                            <td>Отчётность</td>
                        </tr>
                        <tr v-for="discipline in disciplinesList.disciplines">
                            <td>
                                {{ discipline.name }}
                            </td>
                            <td>
                                {{ discipline.student_group_name }}
                            </td>
                            <td>
                                {{ discipline.auditorium_hours }}
                            </td>
                            <td v-bind:class="{
                                'very-low' : (discipline.schedule_hours < discipline.auditorium_hours * 0.5),
                                'low' : (discipline.schedule_hours >= discipline.auditorium_hours * 0.5) && (discipline.schedule_hours < discipline.auditorium_hours * 0.9),
                                'almost-there' : (discipline.schedule_hours >= discipline.auditorium_hours * 0.9) && (discipline.schedule_hours < discipline.auditorium_hours),
                                'exact' : (discipline.schedule_hours == discipline.auditorium_hours),
                                'exact-plus-one' : (discipline.schedule_hours == discipline.auditorium_hours + 1),
                                'more-than-enough' : (discipline.schedule_hours > discipline.auditorium_hours+1)
                             }">
                                {{ discipline.schedule_hours }}
                            </td>
                            <td v-for="month in disciplinesList.month_list">
                                {{ discipline.stat_by_month[month] }}
                            </td>
                            <td>
                                {{ discipline.attestation }}
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
        props: ['disciplinesList', 'selectedTeacher'],
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