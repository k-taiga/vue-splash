<template>
  <div class="container--smalll">
    <ul class="tab">
      <li class="tab__item" :class="{'tab__item--active': tab === 1 }" @click="tab = 1">Login</li>
      <li class="tab__item" :class="{'tab__item--active': tab === 2 }" @click="tab = 2">Register</li>
    </ul>
    <div class="panel" v-show="tab === 1">Login Form
      <div class="panel" v-show="tab === 1">
        <!-- デフォルトのForm送信の挙動をキャンセルする -->
        <form class="form" @submit.prevent="login">
          <div v-if="loginErrors" class="errors">
            <ul v-if="loginErrors.email">
              <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
            </ul>
            <ul v-if="loginErrors.password">
              <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
            </ul>
          </div>
          <label for="login-email">Email</label>
          <!-- v-modelでデータ変数とinput要素を紐付ける -->
          <input type="text" class="form__item" id="login-email" v-model="loginForm.email">
          <label for="login-password">Password</label>
          <input type="password" class="form__item" id="login-password" v-model="loginForm.password">
          <div class="form__button">
            <button type="submit" class="button button--inverse">login</button>
          </div>
        </form>
      </div>
    </div>
    <div class="panel" v-show="tab === 2">Register Form
      <div class="panel" v-show="tab === 2">
        <form class="form" @submit.prevent="register">
          <label for="username">Name</label>
          <input type="text" class="form__item" id="username" v-model="registerForm.name">
          <label for="email">Email</label>
          <input type="text" class="form__item" id="email" v-model="registerForm.email">
          <label for="password">Password</label>
          <input type="password" class="form__item" id="password" v-model="registerForm.password">
          <label for="password-confirmation">Password (confirm)</label>
          <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
          <div class="form__button">
            <button type="submit" class="button button--inverse">register</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
// import { mapState } from 'vuex'

export default {
  data () {
    return {
      tab: 1,
      loginForm: {
        email: '',
        password: ''
      },
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  // 算出プロパティでauthステートを参照する
  computed: {
    apiStatus () {
      return this.$store.state.auth.apiStatus
    },
    loginErrors () {
      return this.$store.state.auth.loginErrorMessages
    }

    // mapStateで上記の記述をまとめられる
    // 機能は算出プロパティとストアのステートをマッピングする
    // ...mapState({
    //   apiStatus: state => state.auth.apiStatus,
    //   loginErrors: state => state.auth.loginErrorMessages
    // })
  },
  methods: {
    async login () {
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch('auth/login', this.loginForm)

      // apiStatusがtrueの場合のみTOPページに移動する
      if (this.apiStatus) {
        this.$router.push('/')
      }
    },
    async register () {
      // dispatchでstoreのメソッド呼び出し 第一引数はアクション,第二引数はLogin.vueで入力したFormの入力値
      // store/index.jsでVue.use(Vuex)をしているため、$storeを呼び出せる
      await this.$store.dispatch('auth/register', this.registerForm)
      // awaitで非同期が返ってきたらrouterのpushメソッドでrouter.jsで定義した/のルートのディレクトリに移動する
      // これもVue.use(VueRouter)で記述しているため使える
      this.$router.push('/')
    }
  }
}
</script>