import './bootstrap'

import Vue from 'vue'

// ルーティングの定義をインポート
import router from './router'

// Vuexのstoreをインポート
import store from './store'

// ルートコンポーネントインポート
import App from './App.vue'

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {App}, //ルートコンポートの使用を宣言
    template: '<App />'
});
