<template>
    <div class="flex-center loader-wrapper">
        <div class="loader"></div>
    </div>
</template>

<script>
    export default {}
</script>

<style lang="sass" scoped>
    .loader
        border: 16px solid #f3f3f3
        border-top: 16px solid #e8e8e8
        border-radius: 50%
        width: 30px
        height: 30px
        animation: spin 500ms linear infinite

    .loader-wrapper
        opacity: 0.8
        background: #e8e8e8
        position: absolute
        width: 100%
        height: 100%
        border-radius: 6px

    @keyframes spin
        0%
            transform: rotate(0deg)
        100%
            transform: rotate(360deg)

</style>