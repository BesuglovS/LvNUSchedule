<template>
    <div class="modal is-active">
        <div class="modal-background"></div>

        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{selectedGroup.name}}
                </p>
                <button class="delete" @click="$emit('close')"></button>
            </header>
            <section class="modal-card-body">
                <div v-if="isEmpty(groupDisciplines)">
                    <h2>Дисциплин нет</h2>
                </div>
                <div v-if="!isEmpty(groupDisciplines)">
                    <table id="modalTeacherDiscipines" class="table is-bordered">
                        <tr>
                            <td>Дисциплина</td>
                            <td>Группа</td>
                            <td>Часов по плану</td>
                            <td>Часов в расписании</td>
                            <td>Отчётность</td>
                        </tr>
                        <tr v-for="discipline in groupDisciplines">
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
        props: ['selectedGroup', 'groupDisciplines'],
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