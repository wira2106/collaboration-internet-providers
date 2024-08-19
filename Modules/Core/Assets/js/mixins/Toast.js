export default {
    methods: {
        Toast(args) {
            var icon = "success",
                title = "",
                message = "";
            if (args.hasOwnProperty('icon')) {
                icon = args.icon;
            }
            if (args.hasOwnProperty('title')) {
                title = args.title;
            }
            if (args.hasOwnProperty('message')) {
                message = args.message;
            }

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            })

            Toast.fire({
                icon: icon,
                title: title
            });
        }
    }
}
