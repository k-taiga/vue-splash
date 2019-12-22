<template>
  <div class="photo-list">
    <div class="grid">
      <Photo
        class="grid__item"
        v-for="photo in photos"
        :key="photo.id"
        :item="photo"
        @like="onLikeClick"
      />
    </div>
    <Pagination :current-page="currentPage" :last-page="lastPage" />
  </div>
</template>

<script>
import { OK } from '../util'
import Photo from '../components/Photo.vue'
import Pagination from '../components/Pagination.vue'

export default {
  components: {
    Photo,
    Pagination
  },
  data () {
    return {
      photos: [],
      currentPage: 0,
      lastPage: 0
    }
  },
  methods: {
    async fetchPhotos () {
      const response = await axios.get(`/api/photos/?page=${this.page}`)

      if (response.status !== OK) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // response.dataでJSON取得、そのJSONの中にdataとして写真データが入っている
      // photoのモデルでjsonに含める値をvisibleとappendでしたやつが入っている
      this.photos = response.data.data
      // APIのレスポンスにlaravel側でpageも入っている
      this.currentPage = response.data.current_page
      this.lastPage = response.data.last_page
    }
  },
  // $routeを監視していてページがここに切り替わったらfetchPhotosしてくる
  watch: {
    $route: {
      async handler () {
        await this.fetchPhotos()
      },
      // またimmediata: trueでコンポーネント作成時にもfetchする
      immediate: true
    }
  }
}
</script>
