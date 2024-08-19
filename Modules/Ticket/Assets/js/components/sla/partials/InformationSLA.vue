<template>
  <div class="col-md-12">
    <div class="card" style="margin-bottom:10px;">
      <div class="card-body">
        ini adalah card informasi SLA
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import Form from "form-backend-validation";
import PermissionsHelper from "../../../../../../Core/Assets/js/mixins/PermissionsHelper";
import Toast from "../../../../../../Core/Assets/js/mixins/Toast";

export default {
  mixins: [Toast, PermissionsHelper],
  props: ["data"],
  data() {
    return {
      form: {
        ticket_id: null,
        start_gangguan: null,
        end_gangguan: null,
      },
    };
  },
  methods: {},
  mounted() {},
  computed: {},
};
</script>
