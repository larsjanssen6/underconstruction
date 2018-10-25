<template>
    <div class="flex-center flex-column full-height-vh" :class="{ body_warning: wrongCode, body_success: success }">
        <loading :active.sync="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"></loading>

        <div class="title">
            {{ title }}
        </div>

        <div v-if="attempts_left != false">
            <p>{{ attempts_left }}</p>
        </div>

        <div v-if="seconds_message != false">
            <p>{{ seconds_message }}</p>
        </div>

        <div class="panel flex flex-column" :class="{ wrong_code: wrongCode, success_code: success }">
            <div class="flex-one">
                <div class="flex full-height">
                    <div class="flex-one number" v-for="number in 4">
                        <div class="flex-center full-height">
                            <h3>{{ code[number - 1] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-three">
                <div class="flex flex-column full-height">
                    <div class="flex-one" v-for="row in 3">
                        <div class="flex full-height">
                            <div class="flex-one number" v-for="number in 3" @click="addNumber(getNumber(row, number))">
                                <div class="flex-center full-height">
                                    <h3>{{ getNumber(row, number) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-one">
                        <div class="flex full-height">

                            <div class="flex-one number" @click="togglePassword">
                                <div class="flex-center full-height">
                                    <h3>{{ toggle_password_text }}</h3>
                                </div>
                            </div>

                            <div class="flex-one number" @click="addNumber(0)">
                                <div class="flex-center full-height">
                                    <h3>0</h3>
                                </div>
                            </div>

                            <div class="flex-two number" @click="back()">
                                <div class="flex-center full-height">
                                    <h3>{{ backButton }}</h3>
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
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        props: [
            'title',
            'backButton',
            'redirectUrl',
            'showButton',
            'hideButton'
        ],

        data() {
            return {
                code: [],
                real_code: [],
                hide_code: true,
                position: 0,
                wrongCode: false,
                success: false,
                seconds_message: false,
                attempts_left: false,
                seconds: 0,
                isLoading: false
            }
        },

        computed: {
            toggle_password_text: function() {
                return this.hide_code ? this.showButton : this.hideButton;
            }
        },

        mounted() {
            this.registerKeys();
            this.resetCode();
            this.checkIfLimited();
        },

        components: {
            Loading
        },

        methods: {

            /**
             * Calculate the number
             */
            getNumber(row, number) {
                return number + (3 - row) * 3;
            },

            /**
             * Add entered number to code array.
             */
            addNumber(number) {
                if(!this.seconds_message) {
                    this.setNumber(number);
                    if(this.codeIsComplete()) {
                        this.isLoading = true;
                        axios.post("/under/check", { "code": this.real_code.join("") })
                            .then(() => {
                                this.success = true;
                                window.location.href = this.redirectUrl;
                            })
                            .catch((error) => {
                                this.wrongCode = true;
                                setTimeout(() => this.wrongCode = false, 5000);
                                if(this.tooManyAttempts(error)) {
                                    this.countDown(error.response.data.seconds_message);
                                }
                                else {
                                    this.attempts_left = error.response.data.attempts_left;
                                }
                                this.resetCode();
                            })
                            .finally(() => {
                                this.isLoading = false;
                            });
                    }
                }
            },

            /**
             * Extract the seconds out of the string. Then
             * start a timer and decrement it every second.
             */
            countDown(message) {
                this.attempts_left = false;
                this.seconds_message = message;
                let timer = setInterval(() => {
                    if(this.seconds == 1) {
                        this.seconds_message = false;
                        this.seconds = 0;
                        clearInterval(timer);
                    }
                    else {
                        this.seconds_message = this.seconds_message.replace(/\d+/g, (match) => {
                            this.seconds = parseInt(match) - 1;
                            return this.seconds;
                        });
                    }
                }, 1000);
            },

            tooManyAttempts(error) {
                return error.response.data.too_many_attempts;
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

            /**
             * Set number at the correct array position.
             */
            setNumber(number) {
                Vue.set(this.real_code, this.position, number);

                if ( this.hide_code ) {
                    Vue.set(this.code, this.position, '-' );
                } else {
                    Vue.set(this.code, this.position, number);
                }
                this.position++;
            },

            /**
             * Check if all numbers are entered.
             */
            codeIsComplete() {
                return this.position == 4;
            },

            /**
             * Register all keyboard numbers.
             */
            registerKeys() {
                document.addEventListener("keydown", (e) => {
                    e.preventDefault();
                    let key = e.which || e.charCode || e.keyCode || 0;

                    if (key == 8) {
                        this.back();

                        return;
                    }

                    let number = parseInt(String.fromCharCode(key));

                    if (isNaN(number)) {
                        return;
                    }

                    if(!this.isLoading) {
                        this.addNumber(number);
                    }
                });
            },

            /**
             * Toggles password display mode
             */
            togglePassword() {
                this.hide_code = !this.hide_code;

                //update already entered code
                this.code = ['*', '*', '*', '*'];
                for ( let i = 0; i < this.position; i++ ) {
                    if ( this.hide_code ) {
                        Vue.set(this.code, i, '-' );
                    } else {
                        Vue.set(this.code, i, this.real_code[i]);
                    }
                }

            },

            /**
             * run at start to check whether user been limited from server
             */
            checkIfLimited() {
                this.isLoading = true;
                axios.post("/under/checkiflimited")
                    .catch((error) => {
                        this.wrongCode = true;
                        setTimeout(() => this.wrongCode = false, 5000);
                        if(this.tooManyAttempts(error)) {
                            this.countDown(error.response.data.seconds_message);
                        }
                        else {
                            this.attempts_left = error.response.data.attempts_left;
                        }
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            }
        }
    }
</script>

<style lang="sass" scoped>

    $success: #27ae60
    $warning: #e74c3c
    $box-shadow: 0 2px 3px 0
    $shadow-color: rgba(0,0,0,.16)
    $mobile-break-point: 750px

    .wrong_code
        box-shadow: $box-shadow $warning

    .success_code
        box-shadow: $box-shadow $success

    .body_warning
        color: $warning

    .body_success
        color: $success

    .flex
        display: flex

        &-one
            flex: 1

        &-two
            flex: 1

        &-three
            flex: 3

        &-center
            @extend .flex
            align-items: center
            justify-content: center

        &-column
            flex-direction: column

    .full-height
        height: 100%

        &-vh
            height: 100vh

    .panel
        width: 300px
        height: 400px
        background: #F8F8FA
        box-shadow: $box-shadow $shadow-color
        border-radius: 6px

    .number
        margin: 10px
        border-bottom: 1px solid #DCDCDE
        cursor: pointer

        div
            h3
                font-size: 15px
                font-weight: 900

        &:hover
            box-shadow: $box-shadow $shadow-color

    .title
        font-size: 84px
        margin-bottom: 40px

    @media only screen and (max-width: $mobile-break-point)
        .title
            display: none

</style>
