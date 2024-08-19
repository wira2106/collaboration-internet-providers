import Toast from './Toast';
import TranslationHelper from './TranslationHelper';

export default {
    mixins: [Toast, TranslationHelper],
    methods: {
        copyToClipboard(text) {
            this.$copyText(text).then((e) => {
                this.Toast({
                    title:this.trans('core.copied'),
                    icon: 'success'
                })
            }, (e) => {
                this.Toast({
                    title:this.trans('core.not copied'),
                    icon: 'info'
                })
            })
        }
    }
}