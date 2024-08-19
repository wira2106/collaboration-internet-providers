import axios from "axios";

export default {
  data() {
    return {
      company_id: "",
      data: [],
      companies: [],
      meta: {
        current_page: 1,
        per_page: 10,
      },
      order_meta: {
        order_by: "",
        order: "ascending",
      },
      links: {},
      searchQuery: "",
      tableIsLoading: false,
      request: null,
      requests: [],
    };
  },
  methods: {
    queryServer(customProperties) {
       
      const cancelSource = axios.CancelToken.source();
      const properties = {
        page: this.meta.current_page,
        per_page: this.meta.per_page,
        order_by: this.order_meta.order_by,
        order: this.order_meta.order,
        search: this.searchQuery,
        company_id: this.company_id,
        cancelToken: cancelSource.token,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(
          route(
            "api.wilayah.wilayah.pagination",
            _.merge(properties, customProperties)
          ),
          properties
        )
        .then((response) => {
           
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.company_id = response.data.selected;
            if (response.data.selected == null) {
              this.company_id = "";
            } else {
              this.company_id = parseInt(response.data.selected);
            }
            this.companies = response.data.companies.map((item) => {
              return {
                label: item.name,
                value: item.company_id,
              };
            });
          }
        })
        .catch((error) => {
           
        });
    },
    fetchData() {
      this.tableIsLoading = true;
      if (this.request) this.cancel();
      this.queryServer();
    },
    handleSizeChange(event) {
      console.log(`per page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({
        per_page: event,
      });
    },
    handleCurrentChange(event) {
      console.log(`current page :${event}`);
      this.tableIsLoading = true;
      this.queryServer({
        page: event,
      });
    },
    handleSortChange(event) {
      console.log("sorting", event);
      this.tableIsLoading = true;
      this.queryServer({
        order_by: event.prop,
        order: event.order,
      });
    },
    performSearch: _.debounce(function(query) {
      console.log(`searching:${query.target.value}`);
      this.tableIsLoading = true;
      this.queryServer({
        search: query.target.value,
      });
    }, 300),
    cancel() {
      this.request.cancel();
    },
    searchFunction() {
      var self = this;
      window.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
          self.fetchData();
        }
      });
    },
    goToEdit(scope) {
      this.$router
        .push({
          name: "admin.wilayah.wilayahs.edit",
          params: {
            wilayah: scope.row.wilayah_id,
          },
        })
        .catch((err) => {
          console.log(err);
        });
    },
    goToPresale(scope) {
      return (window.location.href = route("admin.presale.presale.index", {
        wilayah: scope.row.wilayah_id,
      }));
    },
    goToCreate() {
      this.$router.push({
        name: "admin.wilayah.wilayahs.create",
      });
    },
    goToRequestPresale(scope) {
      Swal.fire({
        title: this.trans("wilayahs.messages.request wilayah"),
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: this.trans("core.ya"),
        cancelButtonText: this.trans("core.tidak"),
      }).then((result) => {
        if (result.value) {
          this.tableIsLoading = true;
          axios
            .post(
              route("api.wilayah.company.request_presale", {
                wilayah: scope.row.wilayah_id,
              })
            )
            .then((response) => {
              this.tableIsLoading = false;
              this.fetchData();
              this.Toast({
                icon: "success",
                title: response.data.message,
              });
            })
            .catch((error) => {
              this.tableIsLoading = false;
              this.Toast({
                icon: "error",
                title: this.trans("core.error 500 title"),
              });
            });
        }
      });
    },
    capitalizeLetter(string) {
      return string.toUpperCase();
    },
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
    classStatusPresale(status) {
      if (status == "review") return "text-purple";
      if (status == "finish") return "text-primary";

      return "text-warning";
    },
  },
  mounted() {
    this.fetchData();
    this.searchFunction();
  },
};
