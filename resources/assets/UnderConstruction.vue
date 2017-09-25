<template>
    <div class="flex-center flex-column full-height-vh" :class="{ body_warning: wrongCode, body_success: success }">
        <div class="title">
            Under construction
        </div>

        <div class="panel flex flex-column" :class="{ wrong_code: wrongCode, success_code: success }" v-cloak>
            <div class="flex-one">
                <div class="flex full-height">
                    <div class="flex-one number">
                        <div class="flex-center full-height">
                            <h3>{{ code[0] }}</h3>
                        </div>
                    </div>

                    <div class="flex-one number">
                        <div class="flex-center full-height">
                            <h3>{{ code[1] }}</h3>
                        </div>
                    </div>

                    <div class="flex-one number">
                        <div class="flex-center full-height">
                            <h3>{{ code[2] }}</h3>
                        </div>
                    </div>

                    <div class="flex-one number">
                        <div class="flex-center full-height">
                            <h3>{{ code[3] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-three">
                <div class="flex flex-column full-height">
                    <div class="flex-one">
                        <div class="flex full-height">
                            <div class="flex-one number" @click="addNumber(7)">
                                <div class="flex-center full-height">
                                    <h3>7</h3>
                                </div>
                            </div>

                            <div class="flex-one number" @click="addNumber(8)">
                                <div class="flex-center full-height">
                                    <h3>8</h3>
                                </div>
                            </div>

                            <div class="flex-one number" @click="addNumber(9)">
                                <div class="flex-center full-height">
                                    <h3>9</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-one">
                        <div class="flex full-height">
                            <div class="flex-one number" @click="addNumber(4)">
                                <div class="flex-center full-height">
                                    <h3>4</h3>
                                </div>
                            </div>

                            <div class="flex-one number" @click="addNumber(5)">
                                <div class="flex-center full-height">
                                    <h3>5</h3>
                                </div>
                            </div>

                            <div class="flex-one number" @click="addNumber(6)">
                                <div class="flex-center full-height">
                                    <h3>6</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-one">
                        <div class="flex full-height">
                            <div class="flex-one number" @click="addNumber(1)">
                                <div class="flex-center full-height">
                                    <h3>1</h3>
                                </div>
                            </div>

                            <div class="flex-one number" @click="addNumber(2)">
                                <div class="flex-center full-height">
                                    <h3>2</h3>
                                </div>
                            </div>

                            <div class="flex-one number" @click="addNumber(3)">
                                <div class="flex-center full-height">
                                    <h3>3</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-one">
                        <div class="flex full-height">
                            <div class="flex-one number" @click="addNumber(0)">
                                <div class="flex-center full-height">
                                    <h3>0</h3>
                                </div>
                            </div>

                            <div class="flex-two number" @click="back()">
                                <div class="flex-center full-height">
                                    <h3>back</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                code: [],
                position: 0,
                wrongCode: false,
                success: false
            }
        },

        created() {
            this.resetCode();
        },

        methods: {
            addNumber(number) {
                this.setNumber(number);

                if(this.codeIsComplete()) {
                    axios.post("/larsjanssen/check", { "code": this.code.join("") })
                        .then(() => {
                            this.success = true;
                            window.location.href = '/';
                        })
                        .catch(() => {
                            this.wrongCode = true;
                            setTimeout(() => this.wrongCode = false, 5000);
                            this.resetCode();
                        });
                }
            },

            back() {
                if(this.position > 0) {
                    Vue.set(this.code, this.position -1, "*");
                    this.position--;
                }
            },

            resetCode() {
                this.code = ['*', '*', '*', '*'];
                this.position = 0;
            },

            setNumber(number) {
                Vue.set(this.code, this.position, number);
                this.position++;
            },

            codeIsComplete() {
                return this.position == 4;
            }
        }
    }
</script>


<style scoped>
    html, body {
        background-color: #F4F4F5;
        color: #636b6f;
        font-family: 'Raleway', sans-serif;
        font-weight: 100;
        height: 100vh;
        margin: 0;
    }

    [v-cloak] {
        display: none;
    }

    .body_warning {
        color: #e74c3c;
    }

    .body_success {
        color: #27ae60;
    }

    .flex {
        display: flex;
    }

    .flex-one {
        flex: 1;
    }

    .flex-two {
        flex: 1;
    }

    .flex-three {
        flex: 3;
    }

    .full-height-vh {
        height: 100vh;
    }

    .full-height {
        height: 100%;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .flex-column {
        flex-direction: column;
    }

    .panel {
        width: 300px;
        height: 400px;
        background: #F8F8FA;
        box-shadow: 0 2px 3px 0 rgba(0,0,0,.16);
        border-radius: 6px;
        overflow: hidden;
    }

    .wrong_code {
        box-shadow: 0 2px 3px 0 #e74c3c;
    }

    .success_code {
        box-shadow: 0 2px 3px 0 #27ae60;
    }

    .number > div >  h3 {
        font-size: 15px;
        font-weight: 900;
    }

    .number {
        margin: 10px;
        border-bottom: 1px solid #DCDCDE;
        cursor: pointer;
    }

    .number:hover {
        box-shadow: 0 2px 3px 0 rgba(0,0,0,.16);
    }

    .title {
        font-size: 84px;
        margin-bottom: 40px;
    }

    @media only screen and (max-width: 720px) {
        .title {
            display: none;
        }
    }
</style>