export default {
  data() {
    return {
      company_id: "",
      highlight: "",
      status_list: "",
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
      user_role: "",
      request: null,
      requests: [],
    };
  },
  methods: {
    queryServer(customProperties) {
      const cancelSource = axios.CancelToken.source();
      const properties = {
        params: {
          page: this.meta.current_page,
          per_page: this.meta.per_page,
          order_by: this.order_meta.order_by,
          order: this.order_meta.order,
          search: this.searchQuery,
          company_id: this.company_id,
          status: this.status_list,
          ...customProperties,
        },
        cancelToken: cancelSource.token,
        ...customProperties,
      };
      this.request = {
        cancel: cancelSource.cancel,
      };
      axios
        .get(route("api.wilayah.pengajuan.pagination"), properties)
        .then((response) => {
          if (typeof response !== "undefined") {
            this.tableIsLoading = false;
            this.data = response.data.data;
            this.meta = response.data.meta;
            this.links = response.data.links;
            this.order_meta.order_by = properties.order_by;
            this.order_meta.order = properties.order;
            this.highlight = this.searchQuery;
            this.companies = response.data.companies.map((item) => {
              return {
                label: item.name,
                value: item.company_id,
                type: item.type,
              };
            });
            if (response.data.selected != null) {
              this.company_id = parseInt(response.data.selected);
            } else {
              this.company_id = "";
            }
          }
        })
        .catch((error) => {});
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
    goToCreate() {
      this.$router.push({
        name: "admin.wilayah.pengajuan.create",
      });
    },
    capitalizeLetter(string) {
      return string.toUpperCase();
    },
    indexMethod(index) {
      return (this.meta.current_page - 1) * this.meta.per_page + index + 1;
    },
    onCancel() {
      $(".modal-form").modal("hide");
    },
    setLabel(item) {
      let val = "";
      val += item.label;
      if (item.type != null) {
        val += " ( " + item.type.toUpperCase() + " )";
      }
      return val;
    },
    goToDetail(scope) {
      this.$router.push({
        name: "admin.wilayah.pengajuan.detail",
        params: {
          id: scope.row.request_wilayah_id,
        },
      });
    },
    perpanjang_kontrak(x, y, request_wilayah_id) {
      Swal.fire({
        title: this.trans("pengajuans.title.pengajuan perpanjang kontrak"),
        type: "info",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Yakin",
        cancelButtonText: "Batal",
        showCloseButton: true,
        showLoaderOnConfirm: true,
      }).then((result) => {
        if (result.value) {
          this.tableIsLoading = true;
          axios
            .post(
              route("api.wilayah.pengajuan.perpanjang", {
                request_wilayah: request_wilayah_id,
              })
            )
            .then((response) => {
              Swal.fire(response.data.message, "", "success");
              this.tableIsLoading = false;
              this.fetchData();
            })
            .catch((err) => {
              this.tableIsLoading = false;
              Swal.fire(this.trans("core.error 500 title"), "", "error");
            });
        }
      });
    },
  },
  mounted() {
    this.getRolesPermission();
    this.fetchData();
  },
  computed: {
    companies_computed: function() {
      return this.companies.filter((item) => {
        if (item.value != 1) return item;
      });
    },
  },
};
