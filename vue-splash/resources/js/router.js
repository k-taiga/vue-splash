import Vue from 'vue'
import VueRouter from 'vue-router'

// ページのコンポーネントを取得
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'
import SystemError from './pages/errors/System.vue'

// store(Vuex)をインストール
import store from './store'

// VueRouterプラグインを使う宣言
// <RouterView />が使えるようになる
Vue.use(VueRouter)

// パスと使用するコンポーネントを紐づける web.phpのようなもの
const routes = [
	{
		path: '/',
		component: PhotoList
	},
	{
		path: '/login',
		component: Login,
		// loginルートにアクセスしコンポーネントが切り替わる前にする処理
		// toはアクセス先のルート、fromはアクセス元のルート、nextはルートの移動先
		beforeEnter (to, from, next) {
			// storeのauth/checkでgetterの値でログイン状態をチェックする
			if (store.getters['auth/check']) {
				next('/')
			} else {
				next()
			}
		}
	},
	{
		path: '/500',
		component: SystemError
	}
]

// VurRouterのインスタンス作成しroutesを渡す
const router = new VueRouter({
	mode: 'history',
	routes
})

// VueRouterをエクスポートする→app.jsにインポート
export default router
