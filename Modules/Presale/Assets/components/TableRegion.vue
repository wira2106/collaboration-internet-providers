<template>
  <el-table
      :data="region"
      style="width: 100%"
      :show-header="false">
      <el-table-column
        prop="name"
        >
      </el-table-column>
      <el-table-column
        prop="action"
        >
        <template slot-scope="scope">
            <p style="margin-bottom:0px;text-align:center" >
                <el-button
                @click="change(scope.row.wilayah_id)"
                size="small"
                type="primary"
                >
                    {{trans('presales.button.change')}}
                </el-button>

            </p>
        </template>
      </el-table-column>
    </el-table>
</template>

<script>
export default {
    props: {
        region: {
            default: []
        },
        change: {
            default: function() {
                console.log("prop change required")
            },
            required:true
        }
    }
}
</script>

<style>

</style>