<template>
  <AppLayout>
    <template #breadcrumb>
      <ItemCrumb title="Lineas" :active="true" link="line"></ItemCrumb>
      <ItemCrumb title="Edit" :active="false" link=""></ItemCrumb>
    </template>
    <form @submit.prevent="update">
      <div
        class="my-4 md:w-3/4 mx-auto shadow flex flex-col bg-white p-5"
      >
        <p class="text-center">EDITAR LINEA</p>
        <JetValidationError></JetValidationError>

        <div class="">
          <Label>Categoria</Label>
          <select v-model="form.category_id" class="w-full h-9">
            <template v-for="(item, index) in categories" :key="index">
              <option :value="item.id">{{ item.name }}</option>
            </template>
          </select>
        </div>

        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="md:w-1/2">
            <Label>Nombre </Label>
            <JetInput v-model="form.name"></JetInput>
          </div>
          <div class="md:w-1/2">
            <Label>Nombre en Inglés</Label>
            <JetInput v-model="form.name_en"></JetInput>
          </div>
        </div>

        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="md:w-1/2">
            <Label>Nombre  2 (opcional) </Label>
            <JetInput v-model="form.short_name"></JetInput>
          </div>
          <div class="md:w-1/2">
            <Label>Nombre 2 en Inglés  (opcional)</Label>
            <JetInput v-model="form.short_name_en"></JetInput>
          </div>
        </div>

        <div class="">
          <Label>Descripción </Label>
          <JetTextAreaVue v-model="form.description"></JetTextAreaVue>
        </div>
        <div class="">
          <Label>Descripción en Inglés</Label>
          <JetTextAreaVue v-model="form.description_en"></JetTextAreaVue>
        </div>

         <div class="flex justify-start items-center py-5">
          <div
            class="
            
              bg-white
              border-2
              rounded
              border-gray-400
              w-6
              h-6
              flex flex-shrink-0
              justify-center
              items-center
              mr-2
              focus-within:border-blue-500
            "
          >
            <input type="checkbox" class="opacity-0 absolute cursor-pointer" id="featured"  v-model="form.featured"/>
            <svg
              class="
                fill-current
                hidden
                w-4
                h-4
                text-indigo-500
                pointer-events-none
              "
              viewBox="0 0 20 20"
            >
              <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
            </svg>
          </div>
          <Label for="featured" class="cursor-pointer">Linea destacada</Label>
        </div>


        <div>
          <Button class="btn-sm">ACTUALIZAR</Button>
        </div>
      </div>
    </form>
  </AppLayout>
  {{form.featured}}
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
    categories: Object,
    line:Object,
  },
  components: {
    AppLayout,
    InputError,
    Button,
    Label,
    JetInput,
    JetTextAreaVue,
    JetValidationError,ItemCrumb,
  },
  data() {
    return {
      form: this.$inertia.form({
        line_id:this.line.id,
        category_id: this.line.category_id,
        featured:this.line.featured?true:false,
        name: this.line.translations.filter(item =>item.locale === 'es')[0].name,
        description: this.line.translations.filter(item =>item.locale === 'es')[0].description,
        name_en: this.line.translations.filter(item =>item.locale === 'en')[0].name,
        description_en: this.line.translations.filter(item =>item.locale === 'en')[0].description,
        short_name: this.line.translations.filter(item =>item.locale === 'es')[0].short_name,
        short_name_en: this.line.translations.filter(item =>item.locale === 'en')[0].short_name,


       

      }),
    };
  },
  methods: {
    update() {
      let data = {
        category_id: this.form.category_id,
        featured:this.form.featured,
        tranlations: [
          {
            name: this.form.name,
            description: this.form.description,
            short_name: this.form.short_name,
            locale: "es",
          },
          {
            name: this.form.name_en,
            description: this.form.description_en,
            short_name: this.form.short_name_en,
            locale: "en",
          },
        ],
      };
      this.$inertia.put(route("line.update",this.line.id), data, {
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
