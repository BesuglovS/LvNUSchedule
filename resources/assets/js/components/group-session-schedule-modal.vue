<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    Расписание сессии ({{selectedGroup.name}})
                </p>
            </header>
            <div class="box">
                <div v-if="exams.length == 0">
                    <h2>Экзаменов нет</h2>
                </div>
                <div v-if="exams.length > 0">
                    <table id="sessionSchedule" class="table is-bordered">
                        <template v-for="exam in exams">
                            <tr>
                                <td>
                                    {{ exam.cons_date }}
                                </td>
                                <td>К</td>

                                <td v-if="exam.cons_time !== exam.exam_time">
                                    {{ exam.cons_time }}
                                </td>
                                <td v-if="exam.cons_time == exam.exam_time" rowspan="2">
                                    {{ exam.cons_time }}
                                </td>

                                <td rowspan="2">
                                    {{ exam.disc_name }}
                                </td>
                                <td rowspan="2">
                                    {{ exam.teacher_fio }}
                                </td>

                                <td v-if="exam.cons_aud !== exam.exam_aud">
                                    {{ exam.cons_aud }}
                                </td>
                                <td v-if="exam.cons_aud == exam.exam_aud" rowspan="2">
                                    {{ exam.cons_aud }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ exam.exam_date }}
                                </td>
                                <td>Э</td>
                                <td v-if="exam.cons_time !== exam.exam_time">
                                    {{ exam.exam_time }}
                                </td>
                                <td v-if="exam.cons_aud !== exam.exam_aud">
                                    {{ exam.exam_aud }}
                                </td>
                            </tr>
                        </template>
                    </table>
                </div>

            </div>
        </div>
        <button class="modal-close" @click="$emit('close')"></button>
    </div>
</template>

<script>
    export default {
        props: ['exams', 'selectedGroup'],
        data: function () {
            return {
            }
        }
    }
</script>