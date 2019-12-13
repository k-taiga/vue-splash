import Vue from 'vue'

// ルーティングの定義をインポート
import router from './router'
// ルートコンポーネントインポート
import App from './App.vue'

const app = new Vue({
    el: '#app',
    router,
    components: {App}, //ルートコンポートの使用を宣言
    template: '<App />'
});