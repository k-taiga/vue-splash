const state = {
  user: null
}

const getters = {}

const mutations = {
// mutationsの第一引数は必ずステートの値
// 実引数は第二引数
  setUser (state, user) {
    state.user = user
  }
}

const actions = {
  // actionsの第一引数は必ずcontext
  async register (context, data) {
    // 会員登録のAPIをPOSTで非同期で呼び出してその結果を待つ
        const response = await axios.post('/api/register', data)
    // action -> commtiでmutation呼び出し -> userステートの更新という流れ
    context.commit('setUser', response.data)
  },
  async login (context, data) {
    const response = await axios.post('/api/login', data)
    context.commit('setUser', response.data)
  },
  async logout (context) {
    const response = await axios.post('/api/logout')
    context.commit('setUser', null)
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
