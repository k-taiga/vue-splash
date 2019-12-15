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
  // actionsの第一引数はcontext
  async register (context, data) {
    // 会員登録のAPIをPOSTで呼び出し
    const response = await axios.post('/api/register', data)
    // action -> commtiでmutation呼び出し -> userステートの更新という流れ
    context.commit('setUser', response.data)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
