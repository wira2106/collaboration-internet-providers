<template>
  <Card class="card card-outline-info">
    <div class="card-header text-white">
      {{ trans("activations.title.rating", { field: title.toUpperCase() }) }}
    </div>
    <div class="card-body no-padding">
      <el-table :data="rating" stripe style="width: 100%">
        <el-table-column
          prop="rating"
          width="180"
          :label="trans('companies.table.rating.rating')"
        >
          <template slot-scope="scope">
            <el-rate
              :value="scope.row.rating / 10"
              disabled
              text-color="#ff9900"
              :max="1"
              show-score
              :score-template="scope.row.rating.toString()"
            >
            </el-rate>
          </template>
        </el-table-column>
        <el-table-column
          prop="description"
          :label="trans('companies.table.rating.description')"
        >
        </el-table-column>
      </el-table>
    </div>
  </Card>
</template>

<script>
export default {
  props: ["title", "rating"],
};
</script>

<style></style>
