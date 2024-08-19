export default {
    data() {
        return {
            swal: {
                loading: () => {
                    Swal.fire({
                        title: "Loading..",
                        html: "",
                        allowOutsideClick: false,
                        onOpen: () => {
                        swal.showLoading();
                        }
                    });
                },
                close: () =>  {
                    Swal.close();
                }
            }
        }
    }
}