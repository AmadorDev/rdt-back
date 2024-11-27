<template>
  <nav class="mt-3">
    <ul class="pagination" v-if="total > 10">
      <template v-for="(item, index) in links" :key="index">
        <li class="page-item " :class="item.active ? 'active' : ''">
          <Link :href="item.url">
            <a class="page-link" >
              {{ item.label }}
            </a></Link
          >
        </li>
      </template>
    </ul>
  </nav>



</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
export default {
  props: {
    total: Number,
    links: Object,
  },
  data() {
    return {};
  },
  components: {
    Link,
  },
};
</script>

<style scoped>




</style>