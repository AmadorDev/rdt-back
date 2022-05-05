<template>
  <textarea
  rows="5" cols="50"
    class="
      text-gray-600
      w-full
      border-gray-300
      focus:border-gray-600
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
