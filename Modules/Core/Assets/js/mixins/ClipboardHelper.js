import Toast from './Toast';
import TranslationHelper from './TranslationHelper';

export default {
    mixins: [Toast, TranslationHelper],
    methods: {
        copyToClipboard(text) {
            console.log(text);
            this.$copyText(text).then((e) => {
                console.log(e);
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