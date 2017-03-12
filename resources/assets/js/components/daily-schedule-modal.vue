<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="box">
                <div v-if="lessons.length == 0">
                    <h2>Занятий нет</h2>
                </div>
                <div v-if="lessons.length > 0">
                    <table id="modalDailySchedule" class="table is-bordered">
                        <tr v-for="lesson in lessons">
                            <td>
                                {{lesson.time}}
                            </td>
                            <td>
                                {{lesson.disc_name}}
                        </td>
                            <td>
                                {{lesson.fio}}
                        </td>
                            <td>
                                {{lesson.aud_name}}
                        </td>
                            <td>
                                {{lesson.group_name}}
                        </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <button class="modal-close" @click="$emit('close')"></button>
    </div>
</template>

<script>
    export default {
        props: ['lessons', 'selectedGroup'],
        data: function () {
            return {
            }
        }
    }
</script>