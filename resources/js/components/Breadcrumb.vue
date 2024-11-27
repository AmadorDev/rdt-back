<template>
  <nav class="text-black font-bold my-2" aria-label="Breadcrumb">
    <ol class="list-none p-0 inline-flex">
     <slot></slot>
    </ol>
  </nav>
</template>

<script>
import ItemCrumb from "./ItemCrumb.vue";
export default {
  components: { ItemCrumb },
};
</script>

