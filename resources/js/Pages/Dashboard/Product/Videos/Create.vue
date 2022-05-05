<template>
  <AppLayout>
    <template #breadcrumb>
      <ItemCrumb title="Productos" :active="true" link="product"></ItemCrumb>
       <ItemCrumb title="Videos" :active="true" link="product_video"></ItemCrumb>
      <ItemCrumb title="Nuevo" :active="false" link=""></ItemCrumb>
    </template>
    <form @submit.prevent="store">
      <div class="my-4 md:w-3/4 mx-auto shadow flex flex-col bg-white p-5">
        <p class="text-center uppercase">REGISTRO VIDEO del producto <strong>{{product.name}}</strong></p>
        <JetValidationError></JetValidationError>

      

        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="w-full">
            <Label>URL DEL VIDEO</Label>
            <JetInput v-model="form.url"></JetInput>
          </div>
        </div>

        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="md:w-1/2">
            <Label>Titulo </Label>
            <JetInput v-model="form.title"></JetInput>
          </div>
          <div class="md:w-1/2">
            <Label>Titulo en ingles</Label>
            <JetInput v-model="form.title_en"></JetInput>
          </div>
        </div>

        <div class="">
          <Label>Contenido </Label>
          <JetTextAreaVue v-model="form.content"></JetTextAreaVue>
        </div>
        <div class="">
          <Label>Contenido en Inglés</Label>
          <JetTextAreaVue v-model="form.content_en"></JetTextAreaVue>
        </div>

        <div>
          <Button class="btn-sm">REGISTRAR</Button>
        </div>
      </div>
    </form>
  </AppLayout>

</template>
<script>
import InputError from "@/Jetstream/InputError";
import Button from "@/Jetstream/Button";

import AppLayout from "@/Layouts/LayoutDashboard.vue";
import Label from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input.vue";
import JetTextAreaVue from "@/Jetstream/TextArea.vue";
import JetValidationError from "@/Jetstream/ValidationErrors.vue";
import ItemCrumb from '@/components/ItemCrumb.vue';

export default {
  props: {
    errors: Object,
    product: Object,
  },
  components: {
    AppLayout,
    InputError,
    Button,
    Label,
    JetInput,
    JetTextAreaVue,
    JetValidationError,ItemCrumb
  },
  data() {
    return {
      form: this.$inertia.form({
        title: "",
        title_en: "",
        content: "",
        content_en: "",
        url: "",
      }),
    };
  },
  methods: {
    store() {
      let data = {
        product_id: this.product.id,
        link:this.form.url,
        tranlations: [
          {
            title: this.form.title,
            content: this.form.content,
            locale: "es",
          },
          {
            title: this.form.title_en,
            content: this.form.content_en,
            locale: "en",
          },
        ],
      };
      this.$inertia.post(route("product_video.store"), data, {
        onSuccess: (page) => {},
        onError: (errors) => {
        
        },
      });
    },
  },
};
</script>
<style lang="css" scoped>
</style>
