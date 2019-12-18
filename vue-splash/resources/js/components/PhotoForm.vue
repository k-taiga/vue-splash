<template>
	<div v-show="value" class="photo-form">
		<div class="photo-form">
			<h2 class="title">Submit a photo</h2>
			<form class="form">
				<input class="form__item" type="file">
				<div class="form__button">
					<button type="submit" class="button button--inverse">submit</button>
				</div>
			</form>
		</div>
	</div>
</template>

<script>
export default {
	props: {
		// valueを受け取るため
		value: {
			// valueは表示、非表示で表現する
			type: Boolean,
			required: true
		}
	}
}
</script>