<template>
  <div :key="keyWork">
    <div class="col-12 d-flex justify-content-end mb-4"></div>
    <div class="col-12 " v-for="(workday, index) in workingDay" :key="index">
      <div class="row">
        <div class="col-5 ">
          <el-form-item :class="{ 'el-form-item is-error': workday.empty }">
            <el-select
              v-model="workday.day"
              size="small"
              :placeholder="trans('workday.form.select day')"
              required
            >
              <el-option
                v-for="item in dayOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              >
              </el-option>
            </el-select>
            <div class="el-form-item__error" v-if="workday.empty">
              {{ trans("core.validation.required", { field: "Day" }) }}
            </div>
          </el-form-item>
        </div>
        <div class="col-5 col-md-6">
          <div class="row">
            <el-time-picker
              size="small"
              is-range
              v-model="workday.time"
              range-separator="T"
              :start-placeholder="trans('workday.form.start time')"
              :end-placeholder="trans('workday.form.end time')"
            >
            </el-time-picker>
          </div>
        </div>
        <div class="col-2 col-md-1">
          <el-tooltip
            :content="trans('core.tooltip.work day.hapus')"
            placement="top"
          >
            <el-button
              type="danger"
              size="mini"
              icon="el-icon-delete"
              @click.prevent="removeWorkDay(index, workday.id)"
            ></el-button>
          </el-tooltip>
        </div>
      </div>
    </div>
    <div class="col-12 d-flex justify-content-center mb-2">
      <el-button
        type="primary"
        icon="el-icon-plus"
        @click.prevent="addWorkDay()"
        :disabled="addWorkingButtonDisable"
        >{{ trans("companies.button.create working-day") }}</el-button
      >
      <el-button
        type="success"
        icon="el-icon-upload"
        :loading="buttonSaveLoading"
        @click.prevent="saveWorkDay"
        >{{ trans("workday.button.save work day") }}</el-button
      >
    </div>
  </div>
</template>

<script>
import Form from "form-backend-validation";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import Toast from "../../../../Core/Assets/js/mixins/Toast";
import axios from "axios";

export default {
  props: ["company_id"],
  mixins: [TranslationHelper, Toast],
  data() {
    return {
      buttonSaveLoading: false,
      keyWork: 1,
      formError: false,
      workingDay: [],
      options: [
        {
          value: "monday",
          label: "Monday",
          disabled: false,
        },
        {
          value: "tuesday",
          label: "Tuesday",
          disabled: false,
        },
        {
          value: "wednesday",
          label: "Wednesday",
          disabled: false,
        },
        {
          value: "thursday",
          label: "Thursday",
          disabled: false,
        },
        {
          value: "friday",
          label: "Friday",
          disabled: false,
        },
        {
          value: "saturday",
          label: "Saturday",
          disabled: false,
        },
        {
          value: "sunday",
          label: "Sunday",
          disabled: false,
        },
      ],
    };
  },
  methods: {
    addWorkDay(days = null) {
      if (this.options.length === this.workingDay.length) {
        this.$notify.info({
          title: this.trans("companies.tab.title.working-day"),
          message: this.trans("companies.validation.max", {
            field: "Working Day",
          }),
        });
        return;
      }
      this.workingDay.push({
        day: days,
        time: [new Date(2016, 9, 10, 8), new Date(2016, 9, 10, 17)],
        is_new: true,
      });
    },
    async removeWorkDay(i, id) {
      if (this.workingDay[i].is_new) {
        this.workingDay.splice(i, 1);
      } else {
        await Swal.fire({
          title: this.trans("core.modal.title"),
          text: this.trans("core.modal.confirmation-message"),
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: this.trans("core.button.confirm"),
          cancelButtonText: this.trans("core.button.cancel"),
          onClose: () => {
            this.$emit("onDeleteSuccess", true);
          },
        })
          .then((result) => {
            if (result.value) {
              axios
                .delete(route("api.company.company.workday.delete", { id: id }))
                .then((response) => {
                  this.workingDay.splice(i, 1);
                  this.Toast({
                    icon: "success",
                    title: response.data.message,
                  });
                });
            }
          })
          .catch((err) => {
            this.Toast({
              icon: "error",
              title: this.trans("core.error 500 title"),
            });
          });
      }
    },
    checkWorkDaySelected(val) {
      return this.workingDay.some((element) => element.day === val);
    },
    dayFormatted() {
      this.workingDay.map((item) => {
        item.jam_mulai = item.time[0].toLocaleTimeString("it-IT");
        item.jam_selesai = item.time[1].toLocaleTimeString("it-IT");
        item.empty = (item.day == null || item.day == "");
        return item;
      });
    },
    saveWorkDay() {
      this.buttonSaveLoading = true;
      this.dayFormatted();
      this.formError = this.workingDay.some((item) => item.empty == true);
      if (!this.formError) {
        let form = new Form({ working_day: this.workingDay });
        form
          .post(
            route("api.company.company.workday", { company: this.company_id })
          )
          .then((response) => {
            this.fetchGetWorkingDay();
            this.Toast({
              icon: "success",
              title: response.message,
            });
            this.buttonSaveLoading = false;
          })
          .catch((err) => {
            this.Toast({
              icon: "error",
              title: this.trans("core.error 500 title"),
            });
            this.buttonSaveLoading = false;
          });
      } else {
        this.buttonSaveLoading = false;
      }
    },
    fetchGetWorkingDay() {
      axios
        .get(
          route("api.company.company.workday.find", {
            company: this.company_id,
          })
        )
        .then((response) => {
          this.workingDay = [];
          response.data.data.map((item) => {
            this.workingDay.push({
              id: item.id,
              day: item.day.toLowerCase(),
              time: [new Date(item.jam_mulai), new Date(item.jam_selesai)],
              is_new: false,
            });
          });

          // if (response.data.data.length == 0) {
          //   this.options.map((item) => this.addWorkDay(item.value));
          // }
        });
    },
  },

  mounted() {
    this.fetchGetWorkingDay();
  },
  computed: {
    addWorkingButtonDisable: function() {
      return this.workingDay.length === this.options.length;
    },
    dayOptions: function() {
      //return this.options;
      return this.options.filter((item) => {
        return !this.workingDay.some((i) => i.day == item.value);
      });
    },
  },
};
</script>

<style></style>
