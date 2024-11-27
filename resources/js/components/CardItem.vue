<template>
  
    <div
    :class="classes"
      class="
        h-full
        p-6
        dark:bg-gray-800
        bg-white
        hover:shadow-xl
        rounded
        border-b-4 border-indigo-500
        shadow-md
        
      "
    >
      <slot></slot>
    </div>
  
</template>

<script>
export default {
    props:{
        classes:String
    }
};
</script>

<style>
</style>