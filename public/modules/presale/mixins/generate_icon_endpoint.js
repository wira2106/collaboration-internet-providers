export default {
    methods: {
        generate_icon_endpoint(type, selected = true) {
            let url = '';
            let prefix = selected ? '-selected' : '-disable'
            switch (type) {
                case 'ODP':
                    url = window.location.origin + `/modules/presale/img/odp-marker${prefix}.ico`;
                    break;
                case 'JB':
                    url = window.location.origin + `/modules/presale/img/jb-marker${prefix}.ico`;
                    break;
            
                default:
                    url = window.location.origin + `/modules/presale/img/fixing-slack-marker${prefix}.ico`;
                    break;
            }

            return url;
        }
    }
}