import Vue from 'vue';
import Questionview from './views/QuestionView'
import store from './store';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';


window.Vue = new Vue({
    components: { Questionview },
    store,
}).$mount('#question')


Vue.use(VueToast, {
    duration: 5000,
    position: 'top-right',
});