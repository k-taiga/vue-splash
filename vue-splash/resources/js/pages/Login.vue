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
    <div class="panel" v-show="tab === 2">Register Form</div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        tab: 1,
        loginForm: {
          email: '',
          password: ''
        },
      }
    }
    methods: {
      login () {
        console.log(this.loginForm)
      }
    }
  }
</script>
