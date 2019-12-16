// 定義したエラーのcodeをインポート
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  registerErrorMessages: null
}

// stateそのものではなくstateから演算した結果を取得したい
const getters = {
  // ログインチェックのgetter
  // 確実に真偽値を返すために二重否定
  check: state => !! state.user,
  // stateにuserがあればそれを返す、なければ空文字を返す
  username: state => state.user ? state.user.name: ''
}

const mutations = {
// mutationsの第一引数は必ずステートの値
// 実引数は第二引数
  setUser (state, user) {
    state.user = user
  },
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages
  }
}

const actions = {
  // 登録
  // actionsの第一引数は必ずcontext
  async register (context, data) {
    context.commit('setApiStatus', null)
    // 会員登録のAPIをPOSTで非同期で呼び出してその結果を待つ
    const response = await axios.post('/api/register', data)

    // 登録のAPIの結果が201なら
    if (response.status === CREATED ) {
      context.commit('setApiStatus', true)
      // action -> commitでmutation呼び出し -> userステートの更新という流れ
      context.commit('setUser', response.data)
      return false
    }

    context.commit('setApiStatus', false)

    if (response.status === UNPROCESSABLE_ENTITY) {
        context.commit('setRegisterErrorMessages', response.data.errors)
     }else {
       context.commit('error/setCode', response.status, { root:true })
     }
  },
  // ログイン
  async login (context, data) {
    context.commit('setApiStatus', null)
    //　通信成功でも失敗でもレスポンスを返す
    const response = await axios.post('/api/login', data)

    if (response.status === OK ) {
      // 成功ならtrue
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    // 失敗ならfalse
     context.commit('setApiStatus', false)

     if (response.status === UNPROCESSABLE_ENTITY) {
      // バリデーションエラーのためルートで移動せず同一コンポーネント内でエラーを出すために、loginErrorMessages にエラーメッセージをセット
        context.commit('setLoginErrorMessages', response.data.errors)
     }else {
       // バリデーションエラー以外ならerrorストアのステートを更新する
       // 別のストアのミューテーションにコミットする場合は{ root:true }が必要
       context.commit('error/setCode', response.status, { root:true })
     }
  },
  // ログアウト
  async logout (context) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/logout')

    if (response.status === OK ) {
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },
  async currentUser (context) {
    context.commit('setApiStatus', null)
    const response = await axios.get('/api/user')
    // response.dataが空の場合はnullを入れてそれをsetUserする
    const user = response.data || null

    if (response.status === OK ) {
      context.commit('setApiStatus', true)
　   context.commit('setUser', user)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  }
}

export default {
  // これでauth/regiterとかで一意に呼び出せる
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
