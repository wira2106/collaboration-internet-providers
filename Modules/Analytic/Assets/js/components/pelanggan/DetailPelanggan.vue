<template>
  <div>
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("analytics.title.analytics") }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'admin.analytic.pelanggan.index' }">
              {{ trans("analytics.title.analytics") }}
            </router-link>
          </li>
          <li class="breadcrumb-item">
            {{ trans(`analytics.detail resource`) }}
          </li>
        </ol>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline-info">
            <div class="card-header">
              <h5 class="card-title text-white">
                {{ trans(`analytics.detail resource`) }}
              </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <h4 @click="fetchData">
                        {{ trans("analytics.title.activity pelanggan") }} -
                        <router-link
                          :to="{
                            name: 'admin.pelanggan.form.detail',
                            params: {
                              pelanggan_id: data.pelanggan.pelanggan_id,
                            },
                          }"
                        >
                          {{ data.pelanggan.nama_pelanggan }}
                        </router-link>
                      </h4>
                    </div>
                    <div class="col-md-8">
                      <table class="table small">
                        <tr>
                          <td>
                            {{ trans("analytics.table.didaftarkan pada") }}
                          </td>
                          <td>:</td>
                          <td>{{ data.pelanggan.tanggal_buat }}</td>
                        </tr>
                        <tr>
                          <td>{{ trans("analytics.table.isp") }}</td>
                          <td>:</td>
                          <td>{{ data.pelanggan.nama_isp }}</td>
                        </tr>
                        <tr>
                          <td>{{ trans("analytics.table.osp") }}</td>
                          <td>:</td>
                          <td>{{ data.pelanggan.nama_osp }}</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <AnalyticWithGraphic :data="activity_isp" :title='trans("analytics.table.isp")' :pelanggan="data.pelanggan" v-if="data.pelanggan.pelanggan_id"/>
          <AnalyticWithGraphic :data="activity_osp" :title='trans("analytics.table.osp")' :pelanggan="data.pelanggan" v-if="data.pelanggan.pelanggan_id"/>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-footer">
                <el-button @click="$router.go(-1)">
                  {{ trans("core.back") }}
                </el-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Toast from "../../../../../Core/Assets/js/mixins/Toast";
import PermissionsHelper from "../../../../../Core/Assets/js/mixins/PermissionsHelper";
import AnalyticWithGraphic from './AnalyticWithGraphic.vue';

export default {
  components: {
    AnalyticWithGraphic
  },
  mixins: [Toast, PermissionsHelper],
  data() {
    return {
      data: {
        pelanggan: { nama_pelanggan: "", pelanggan_id: null },
        activity: [],
      },
    };
  },
  computed: {
    activity_isp() {
      return this.data.activity.filter(item => {
        return item.type == 'isp' 
      })
    },
    activity_osp() {
      return this.data.activity.filter(item => item.type == 'osp' || item.type == 'total' || item.type == 'estimasi')
    }
  },
  methods: {
    fetchData() {
      axios
        .get(
          route("admin.api.analytic.pelanggan.detail", {
            pelanggan: this.$route.params.pelanggan,
          })
        )
        .then((response) => {
          let data = response.data;
          this.data = data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>
<style>
.progress-bar {
  height: 20px;
  width: 0px;
  max-width: 655px;
  animation-delay: 5s;
}
.progress-bar.isp {
  background: var(--blue);
}
.progress-bar.osp {
  background: var(--green);
}
.badge-green {
  display: inline-block;
  width: 50px;
  height: 12px;
  background: var(--green);
}
.badge-blue {
  display: inline-block;
  width: 50px;
  height: 12px;
  background: var(--blue);
}
.estimasi {
  background: var(--purple);
}
</style>
