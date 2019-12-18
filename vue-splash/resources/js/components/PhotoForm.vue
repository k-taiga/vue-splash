<template>
	<div v-show="value" class="photo-form">
		<h2 class="title">Submit a photo</h2>
		<form class="form" @submit.prevent="submit">
      <div v-if="errors" class="errors">
        <ul v-if="errors.photo">
          <li v-for="msg in errors.photo" :key="msg">{{ msg }}</li>
        </ul>
      </div>
			<input class="form__item" type="file" @change="onFileChange">
			<output class="form__output" v-if="preview">
				<img :src="preview" alt="">
			</output>
			<div class="form__button">
				<button type="submit" class="button button--inverse">submit</button>
			</div>
		</form>
	</div>
</template>

<script>
<!-- エラーコードインポート -->
import { CREATED, UNPROCESSABLE_ENTITY } from '../util'
export default {
	props: {
		// valueを受け取るため
		value: {
			// valueは表示、非表示で表現する
			type: Boolean,
			required: true
		}
	},
	data () {
		return {
			preview: null,
			// 選択中のファイルを格納するため
			photo: null,
			errors: null
		}
	},
	methods: {
		// フォームでファイルが選択されたら実行
		onFileChange (event) {
			// 何も選択されていなかったら処理を中断する
			if (event.target.files.length === 0) {
				this.reset()
				return false
			}

			// ファイルが画像じゃなかったら処理を中断する
			// 正規表現でファイルのタイプ属性がimageかチェック
			if (! event.target.files[0].type.match('image.*')) {
				this.reset()
				return false
			}

			// FileReaderクラスのインスタンスを作成
			const reader = new FileReader()

			// ファイルを読み込み終わったタイミングでする処理
			reader.onload = e => {
				// previewに読み込み結果を代入する
				// previewに値が入ると<output>につけたv-ifがtrueと判定される
	      // また<output>内部の<img>のsrc属性はpreviewの値を参照しているので
	      // 結果として画像が表示される
				this.preview = e.target.result
			}

			// ファイルを読み込みデータURL形式で受け取る
			reader.readAsDataURL(event.target.files[0])

			this.photo = event.target.files[0]
		},
		reset () {
			this.preview = ''
			this.photo = null
			// this.$elはコンポーネントそのもののDOM要素を指す
			this.$el.querySelector('input[type="file"]').value = null
		},
		async submit () {
			// HTML5 の FormData API
			const formData = new FormData()
			// 送信したいものをappendする
			formData.append('photo', this.photo)
			const response = await axios.post('/api/photos', formData)

			if (response.status === UNPROCESSABLE_ENTITY) {
				this.errors = response.data.errors
				return false
			}

			this.reset
			// inputイベントを発行して自動的にフォームを閉じる
			// NavbarのshowFormの値をfalseにしてその値を自身で受け取る
			this.$emit('input', false)

			if (response.status !== CREATED) {
				this.errors = response.data.errors
				return false
			}

			this.$router.push(`/photos/${response.data.id}`)
		}
	}
}
</script>
