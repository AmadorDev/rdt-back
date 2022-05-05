<template>
  <span
    :class="classes"
    class="
      inline-block
      rounded-full
      px-2
      py-1
      text-xs
      font-bold
      space-x-1
      cursor-pointer
      shadow-md
      shadow-gray-600
    "
  >
    <slot></slot>
  </span>
</template>

<script>
export default {
  props: {
    classes: String,
  },
};
</script>

<style>
</style>