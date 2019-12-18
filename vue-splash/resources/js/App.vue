<template>
	<div>
    <header>
      <Navbar />
    </header>
		<main>
			<div class="container">
        <Message />
				<!-- 切り替わる場所の特定 -->
				<RouterView />
			</div>
		</main>
    <Footer />
	</div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
import Message from './components/Message.vue'

import { INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    Navbar,
    Footer,
    Message
  },
  // ストアのステートを算出プロパティで参照
  computed: {
  	errorCode () {
  		return this.$store.state.error.code
  	}
  },
  // それをwatchで監視する
  watch: {
  	errorCode: {
  		handler (val) {
  			if (val === INTERNAL_SERVER_ERROR) {
  				this.$router.push('/500')
  			}
  		},
  		immediate: true
  	},
  	$route () {
  		this.$store.commit('error/setCode', null)
  	}
  }
}
</script>
