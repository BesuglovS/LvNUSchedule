<template>
    <div class="col-xs-12 col-sm-6">
        <table id="dailyScheduleTable">
            <tr>
                <td colspan="2">
                    <datepicker language = "ru"
                                id="dailydatepicker"
                                :inline="true"
                                :monday-first = "true"
                                format = "dd.MM.yyyy"
                                v-model = "date"
                                v-on:selected="dateChanged">
                    </datepicker>
                </td>
            </tr>
            <tr>
                <td class="text-align-center">
                    <button type="button"
                            id="today"
                            v-on:click="today()"
                            class="btn btn-primary">
                        Сегодня
                    </button>
                </td>
                <td class="text-align-center">
                    <button type="button"
                            id="tomorrow"
                            v-on:click="tomorrow()"
                            class="btn btn-primary">
                        Завтра
                    </button>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    var moment = require('moment');
    moment.locale('ru');

    export default {
        mounted() {
            this.date = new Date();
        },
        props: ['datepickerDate'],
        data: function () {
            return {
                date: null
            }
        },
        components: {
            Datepicker
        },
        methods: {
            dateChanged(date) {
                this.$emit('dateChanged', date);
            },
            today() {
                let newDate = new Date();

                this.date = newDate;
                this.dateChanged(newDate);
            },
            tomorrow() {
                let today = moment();
                let tomorrow = moment(today).add(1, 'day');
                let newDate = tomorrow.toDate();

                this.date = newDate;
                this.dateChanged(newDate);
            }
        }
    }
</script>