import Vue from 'vue'
import VueRouter from 'vue-router'

// ページのコンポーネントを取得
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'

// VueRouterプラグインを使う宣言
// <RouterView />が使えるようになる
Vue.use(VueRouter)

// パスと使用するコンポーネントを紐づける　web.phpのようなもの
const routes = [
	{
		path: '/',
		component: PhotoList
	},
	{
		path: '/login',
		component: Login
	}
]

// VurRouterのインスタンス作成しroutesを渡す
const router = new VueRouter({
	mode: 'history',
	routes
})

// VueRouterをエクスポートする→app.jsにインポート
export default router