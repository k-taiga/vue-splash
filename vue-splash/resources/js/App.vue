<template>
	<div>
    <header>
      <Navbar />
    </header>
		<main>
			<div class="container">
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

export default {
  components: {
    Navbar,
    Footer
  }
}
</script>
