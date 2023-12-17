import {usePage,} from "@inertiajs/vue3";
import {inject, ref, defineEmits} from 'vue'


export default {
    // emits: ['showToast'],
    // setup(props, ctx) {
    //     ctx.emit('showToast')
    // },

    data() {
        return {
            user: null,
        }
    },

    mounted() {
        // console.log(inject('toast'));
        if (usePage().props.auth)
            this.user = usePage().props.auth.user
    },

    methods: {
        isAdmin() {
            return usePage().props.isAdmin;
        },
        csrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        },
        showWalletChargeDialog() {
            this.emitter.emit('showWalletChargeDialog', null);
        },
        showToast(type, message) {
            this.emitter.emit('showToast', {type, message});
        },
        showAlert(type, message) {
            this.emitter.emit('showAlert', {type, message});

        },
        showDialog(type, message, button, action, items = null) {

            this.emitter.emit('showDialog', {type, message, button, action, items});

        },
        isLoading(loading) {
            this.emitter.emit('loading', loading);

        },
        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        /**
         * Translate the given key.
         */
        __: (key, replace = {}) => {
            let $lang = usePage().props.language;

            var translation = $lang[key]
                ? $lang[key]
                : key

            Object.keys(replace).forEach(function (key) {
                translation = translation.replace(':' + key, replace[key])
            });

            return translation
        }, dir: () => {
            let $lang = usePage().props.language;
            if ($lang == 'en') return 'ltr'; else return 'rtl';

        }, log: (str) => {
            console.log(str);
        },
        asPrice(price) {
            if (!price) return 0;
            // return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            return price.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        },
        cropText(str, len, trailing = "...") {
            return str && str.length >= len ? `${str.substring(0, len)}${trailing}` : str
        },
        getCategory(id) {
            if (id == null || usePage().props.categories == null) return '';
            for (const el of usePage().props.categories)
                if (el.id == id)
                    return this.__(el.name);
            return '';
        },
        getProvince(id) {

            if (id == null || usePage().props.provinces == null) return '';
            for (const el of usePage().props.provinces)
                if (el.id == id)
                    return this.__(el.name);
            return '';
        },
        getCounty(id) {
            if (id == null || usePage().props.counties == null) return '';
            for (const el of usePage().props.counties)
                if (el.id == id)
                    return this.__(el.name);
            return '';
        },
        getStatus(type, id) {
            if (id == null || type == null || (usePage().props[`statuses`] == null && usePage().props[type] == null)) return {
                name: '',
                color: 'primary'
            };
            if (usePage().props[`${type}`])
                for (const el of usePage().props[`${type}`])
                    if (el.name == id)
                        return {name: this.__(el.name), color: el.color || 'primary'};

            for (const el of usePage().props[`statuses`])
                if (el.name == id)
                    return {name: this.__(el.name), color: el.color || 'primary'};

        },
        getErrors(error) {
            if (error.response) {
                if (error.response.status == 419)
                    location.reload();
                if (error.response.data && error.response.data.errors)
                    return Object.values(error.response.data.errors).join("<br/>")
                if (error.response.data && error.response.data.message)
                    if (error.response.data.message == 'Unauthenticated.')
                        return this.__('first_login_or_register');
                return error.response.data.message;

            } else if (error.request) {
                return error.request;
            } else {
                return error.message;
            }
        },
        hasAccess(role) {
            if (this.isAdmin()) return true;
            if (!this.user || !this.user.access) return false;
            return this.user.access.split(',').indexOf(role) > -1;
        },
        hasWallet() {

            return this.user ? this.user.wallet_active : false;
        },
        f2e(num) {

            return window.f2e(num);
        },
        getDuration(sec) {
            if (sec == null || sec == 0) return '0';
            var sec_num = parseInt(sec, 10); // don't forget the second param
            var hours = Math.floor(sec_num / 3600);
            var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
            var seconds = sec_num - (hours * 3600) - (minutes * 60);

            if (hours < 10) {
                hours = "0" + hours;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            if (seconds < 10) {
                seconds = "0" + seconds;
            }
            return hours + ':' + minutes + ':' + seconds;
        },
        toShamsi(day, time = false) {
            var t = new Date().getTime();
            if (!day) return '';
            else
                var today = new Date(day);
            let options = {
                hour12: false,

                year: 'numeric',
                month: '2-digit',
                day: '2-digit',

                calendar: 'persian',
            };
            if (time)
                options = {
                    ...options,
                    hour: '2-digit',
                    minute: '2-digit',
                }
//                var dd = String(today.getDate()).padStart(2, '0');
//                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
//                var yyyy = today.getFullYear();
//                return yyyy + '/' + mm + '/' + dd;

            return f2e(today.toLocaleDateString('fa-IR', options));
        }
        ,
        replaceAll(str, find, replace) {
            return str.replace(new RegExp(find, 'g'), replace);

        },
        copyToClipboard(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;

            // Avoid scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                var successful = document.execCommand('copy');
                this.showToast('success', this.__('copy_to_clipboard_successfully'))
            } catch (err) {

            }

            document.body.removeChild(textArea);
        },
        myMap(arr, callbackFn) {
            var tmp = [];
            for (var i = 0; i < arr.length; i++) {
                tmp.push(callbackFn(arr[i]));
            }
            return tmp;
        },
        scrollTo(el) {
            window.scroll({
                top: document.querySelector(el) ? document.querySelector(el).offsetTop : 0,

                behavior: "smooth",
            });
        }
    },


}
