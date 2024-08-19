<template>
    <el-button
            size="mini"
            @click.prevent="goToEditPage()">
        <i class="el-icon-edit"></i>
    </el-button>
</template>

<script>
    export default {
        props: {
            to: { type: Object, required: true },
        },
        methods: {
            goToEditPage() {
                this.$router.push(this.to);
            },
        },
    };
</script>
