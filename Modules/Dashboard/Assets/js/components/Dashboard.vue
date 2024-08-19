<template>
  <div>
     
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">
          {{ trans("core.breadcrumb.dashboard") }}
          {{ dasboardTitle }}
        </h3>
      </div>
      <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <router-link :to="{ name: 'dashboard.index' }">
              {{ trans("core.breadcrumb.home") }}
            </router-link>
          </li>
        </ol>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <template v-for="(widget, index) in widgets">
          <html-fragment :html="widget.html" :key="index" />
        </template>
      </div>
    </div>
    <div class="clearfix"></div>

    <div
      class="modal fade"
      id="myModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">
              {{ trans("dashboard.add widget to dashboard") }}
            </h4>
          </div>
          <div class="modal-body">
            <div class="row"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import HtmlFragmentVue from "../../../../Core/Assets/js/components/HtmlFragment.vue";
import TranslationHelper from "../../../../Core/Assets/js/mixins/TranslationHelper";
import PermissionHelper from "../../../../Core/Assets/js/mixins/PermissionsHelper";

export default {
  mixins: [TranslationHelper, PermissionHelper],
  components: {
    "html-fragment": HtmlFragmentVue,
  },
  data() {
    return {
      widgets: [],
    };
  },
  methods: {
    getWidgets() {
      axios
        .get(route("api.dashboard.widget"))
        .then((response) => {
           
          this.widgets = response.data;
        })
        .catch((error) => {
          console.log("error get widget");
        });
    },
  },
  computed: {
    dasboardTitle: function() {
      var title = "";
      if (this.user_roles.name === "Super Admin") {
        title = "Openaccess";
      } else if (this.user_roles.name === "Admin ISP") {
        title = "ISP";
      } else {
        title = "OSP";
      }
      return title;
    },
  },
  mounted() {
     
    this.getWidgets();
  },
};
</script>

<style></style>
