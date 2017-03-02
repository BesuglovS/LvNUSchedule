<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        {{ message }} <br />
                         {{ reversed }} <br />
                        <input type="text" v-model="newName">
                        <br />
                        <button type="submit" @click="add">
                        Add name
                        </button>
                        <br />
                        <ul>
                            <template v-for="name in names">
                                <li>{{ name.n }} ({{ name.i }})</li>
                            </template>
                        </ul>
                        <h4>Names 0</h4>
                        {{zeroedNames}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: {
            'message': {
                default: ''
            }
        },
        computed: {
            reversed() {
                return this.message.split('').reverse().join('');
            },
            zeroedNames() {
                return this.names.filter(n => n.i === 0).map(n => n.n);
            }
        },
        data: function () {
            return {
                count: 0,
                names: [
                    {n: 'Bob', i: 0},
                    {n: 'Jane', i: 1},
                    {n: 'Fusan', i: 1},
                    {n: 'House', i: 0}],
                newName: ''
            }
        },
        methods: {
            add() {
                this.names.push({n:this.newName, i: 0});
                this.newName = '';
            }
        }
    }
</script>
