<template>
  <input
    class="
      text-gray-600
      w-full
      h-9
      pl-2
      border-2 border-gray-300
      focus:border-blue-700
      outline-none
    "
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    ref="input"
  />
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: ["modelValue"],

  emits: ["update:modelValue"],

  methods: {
    focus() {
      this.$refs.input.focus();
    },
  },
});
</script>
