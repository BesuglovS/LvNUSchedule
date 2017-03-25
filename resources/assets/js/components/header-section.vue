<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="row vertical-align-flex" id="headerRow">
                <div id="mainLogoDiv" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0">
                    <img src="images/DVZ.png" id="mainLogo" >
                </div><!--
             --><div id="mainHeaderDiv" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0">
                    <h1>Диспетческая учебного отдела СГОАН</h1>
                </div><!--
             --><div id="weekHeaderDiv" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0">
                    <!--<div id="weekTitle">Неделя</div>-->
                    <div id="weekNum">{{weekNumber}}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
        },
        props: ['weekNumber'],
        computed: {
        },
        data: function () {
            return {
            }
        },
        methods: {

        }
    }
</script>
