<template>
  <div class="justify-center">
    <div class="md:w-1/2 mx-auto">
      <jet-section-title>
        <template #title><slot name="title"></slot></template>
        <template #description><slot name="description"></slot></template>
      </jet-section-title>
      <form @submit.prevent="$emit('submitted')">
        <div
          class="px-4 py-5 bg-white sm:p-6 shadow"
          :class="
            hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'
          "
        >
          <div class="">
            <slot name="form"></slot>
          </div>
        </div>

        <div
          class="
            flex
            items-center
            justify-end
            px-4
            py-3
            bg-gray-50
            text-right
            sm:px-6
            shadow
            sm:rounded-bl-md sm:rounded-br-md
          "
          v-if="hasActions"
        >
          <slot name="actions"></slot>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import JetSectionTitle from "./SectionTitle.vue";

export default defineComponent({
  emits: ["submitted"],

  components: {
    JetSectionTitle,
  },

  computed: {
    hasActions() {
      return !!this.$slots.actions;
    },
  },
});
</script>
