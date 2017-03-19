<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{selectedGroup.name}} ({{dateformatted}})
                </p>
            </header>
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
                                <span v-if="selectedGroup.name !== lesson.group_name"> ({{lesson.group_name}})</span>
                            </td>
                            <td>
                                {{lesson.fio}}
                            </td>
                            <td>
                                {{lesson.aud_name}}
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
        props: ['lessons', 'selectedGroup', 'dateformatted'],
        data: function () {
            return {
            }
        }
    }
</script>