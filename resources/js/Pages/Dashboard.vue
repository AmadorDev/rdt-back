<template>
  <app-layout>
    <div
      class="
        flex flex-col
        md:flex-row md:flex-wrap md:space-x-2
        justify-center
        content-center
        
        ms:space-y-2
      "
    >
      <div class="md:w-1/4 py-3">
        <CardItem>
          <h3 class="text-2xl mb-3 font-semibold inline-flex">
            {{ category }}
          </h3>
          <p class="text-lg">
            <Link :href="route('category')">
              CATEGORIA
              <i class="fa fa-chevron-right" aria-hidden="true"></i
            ></Link>
          </p>
        </CardItem>
      </div>
      <div class="md:w-1/4 py-3">
        <CardItem>
          <h3 class="text-2xl mb-3 font-semibold inline-flex">{{line}}</h3>
          <p class="text-lg">
            <Link :href="route('line')">
              LINEAS
              <i class="fa fa-chevron-right" aria-hidden="true"></i
            ></Link>
          </p>
        </CardItem>
      </div>
      <div class="md:w-1/4 py-3">
        <CardItem>
          <h3 class="text-2xl mb-3 font-semibold inline-flex">{{product}}</h3>
          <p class="text-lg">
            <Link :href="route('product')">
              PRODUCTOS
              <i class="fa fa-chevron-right" aria-hidden="true"></i
            ></Link>
          </p>
        </CardItem>
      </div>
      <div class="md:w-1/4 py-3">
        <CardItem>
          <h3 class="text-2xl mb-3 font-semibold inline-flex">{{salon}}</h3>
          <p class="text-lg">
            <Link :href="route('salon')">
              SALONES
              <i class="fa fa-chevron-right" aria-hidden="true"></i
            ></Link>
          </p>
        </CardItem>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/LayoutDashboard.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import CardItem from "@/components/CardItem.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  props: {
    category: Number,
    line: Number,
    product: Number,
    salon: Number,
  },
  components: {
    AppLayout,
    Welcome,
    CardItem,
    Link,
  },
});
</script>
