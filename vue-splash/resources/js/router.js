import Vue from 'vue'
import VueRouter from 'vue-router'

// ページのコンポーネントを取得
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'
import SystemError from './pages/errors/System.vue'
import PhotoDetail from './pages/PhotoDetail.vue'

// store(Vuex)をインストール
import store from './store'

// VueRouterプラグインを使う宣言
// <RouterView />が使えるようになる
Vue.use(VueRouter)

// パスと使用するコンポーネントを紐づける web.phpのようなもの
const routes = [
	{
		path: '/',
		component: PhotoList,
		props: route => {
			const page = route.query.page
			// routeのクエリパラメータの値をpageで返す
			// 正規表現で整数であればpage * 1,そうじゃなければ1
			return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1}
		}
	},
	{
		path: '/photos/:id',
		component: PhotoDetail,
		// props: trueでPhotoDetailコンポーネントに:idの値がpropsとして渡される
		props: true
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
	scrollBehavior () {
		return { x: 0, y: 0}
	},
	routes
})

// VueRouterをエクスポートする→app.jsにインポート
export default router
