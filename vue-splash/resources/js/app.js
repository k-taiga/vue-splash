import './bootstrap'

import Vue from 'vue'

// ルーティングの定義をインポート
import router from './router'

// Vuexのstoreをインポート
// store/index.jsでVuex読み込む（storeの実態はauth.js)
import store from './store'

// ルートコンポーネントインポート
import App from './App.vue'

const createApp = async () => {
	await store.dispatch('auth/currentUser').catch(() => {})

	new Vue({
		el: '#app',
		router,
		store,
		components: { App },
		template: '<App />'
	})
}

createApp()