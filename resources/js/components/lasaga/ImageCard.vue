<template>
  <div class="image-upload">
    <div class="image-edit text-gray-900 "  >
      <slot name="icons" ></slot>
    </div>

    <div class="preview">
      <div id="imagePreview">
        <img :src="image" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
    props: {
        image:String
    }
};
</script>

<style>
</style>