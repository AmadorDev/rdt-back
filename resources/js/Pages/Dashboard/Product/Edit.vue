<template>
  <AppLayout>
    <template #breadcrumb>
      <ItemCrumb title="Productos" :active="true" link="product"></ItemCrumb>
      <ItemCrumb title="Edit" :active="false" link=""></ItemCrumb>
    </template>
    <form @submit.prevent="update">
      <div class="my-4 md:w-3/4 mx-auto shadow flex flex-col bg-white p-5">
        <p class="text-center">EDITAR PRODUCTO</p>
        <JetValidationError></JetValidationError>

        <div class="">
          <Label>Linea</Label>
          <select v-model="form.linea_id" class="w-full h-9">
            <template v-for="(item, index) in lines" :key="index">
              <option :value="item.id">{{ item.name }}</option>
            </template>
          </select>
        </div>

        <!-- <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="flex-1">
            
            <Label>Imagen</Label>
            <JetInput
            class="py-1 "
              @input="form.photo = $event.target.files[0]"
              type="file"
            ></JetInput>
          </div>
        </div> -->

        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="md:w-1/2">
            <Label>Nombre del producto </Label>
            <JetInput v-model="form.name"></JetInput>
          </div>
          <div class="md:w-1/2">
            <Label>Nombre del producto en ingles</Label>
            <JetInput v-model="form.name_en"></JetInput>
          </div>
        </div>

        <div class="">
          <Label>Descripción </Label>
          <JetTextAreaVue v-model="form.description"></JetTextAreaVue>
        </div>
        <div class="">
          <Label>Descripción en inglés</Label>
          <JetTextAreaVue v-model="form.description_en"></JetTextAreaVue>
        </div>

        <div>
          <Button class="btn-sm">ACTUALIZAR</Button>
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
    lines:Object,
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
        linea_id: this.product.linea_id,
        name: this.product.translations.filter(item =>item.locale === 'es')[0].name,
        name_en: this.product.translations.filter(item =>item.locale === 'en')[0].name,
        description: this.product.translations.filter(item =>item.locale === 'es')[0].description,
        description_en: this.product.translations.filter(item =>item.locale === 'en')[0].description,
        photo: "",
      }),
    };
  },
  methods: {
    update() {
      let data = {
        linea_id: this.form.linea_id,
        photo: this.form.photo,
        tranlations: [
          {
            name: this.form.name,
            description: this.form.description,
            locale: "es",
          },
          {
            name: this.form.name_en,
            description: this.form.description_en,
            locale: "en",
          },
        ],
      };
      this.$inertia.post(route("product.update",this.product.id), data, {
        onSuccess: (page) => {},
        onError: (errors) => {
          console.log(errors);
        },
      });
    },
  },
};
</script>
<style lang="css" scoped>
</style>
