<template>
    <div id="mainHeaderDiv" class="vcenter col-md-3 col-sm-8 col-xs-8 col-xs-offset-2 ">
        <h1>Диспетческая учебного отдела СГОАН</h1>
    </div>
    <div id="weekHeaderDiv" class="vcenter col-md-2 col-sm-4 col-xs-8 col-xs-offset-2 col-md-offset-0">
        <!--<div id="weekTitle">Неделя</div>-->
        <div id="weekNum">{{weekNumber}}</div>
    </div>
    <div id="mainLogoDiv" class="vcenter col-md-3 col-sm-4 col-xs-12 col-sm-offset-2 col-md-offset-0">
        <img src="images/DVZ.png" id="mainLogo" >
    </div>
</template>

<script>
    export default {
        created() {
        },
        props: ['weekNumber'],
        computed: {

        },
//        data: function () {
//            return {
//                weekNumber: ''
//            }
//        },
        methods: {

        }
    }
</script>
