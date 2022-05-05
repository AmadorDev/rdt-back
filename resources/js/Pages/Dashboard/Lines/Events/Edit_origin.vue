<template>
  <AppLayout>
    <template #breadcrumb>
      <ItemCrumb title="Eventos" :active="true" link="event"></ItemCrumb>
      <ItemCrumb title="Editar" :active="false" link=""></ItemCrumb>
    </template>
    <form @submit.prevent="update">
      <div class="my-4 md:w-3/4 mx-auto shadow flex flex-col bg-white p-5">
        <p class="text-center">EDITAR EVENTO</p>
        <JetValidationError></JetValidationError>

        <div class="">
          <Label>Linea</Label>
          <select v-model="form.linea_id" class="w-full h-9">
            <template v-for="(item, index) in lines" :key="index">
              <option :value="item.id">{{ item.name }}</option>
            </template>
          </select>
        </div>

        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="md:w-1/2">
            <Label>Fecha del evento </Label>
            <JetInput v-model="form.date_event" type="date"></JetInput>
          </div>
          <div class="md:w-1/2">
            <Label>Imagen</Label>
            <JetInput
              @input="form.photo = $event.target.files[0]"
              type="file"
            ></JetInput>
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
          <Button class="btn-sm" type="submit">ACTUALIZAR</Button>
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
    lines: Object,
    event:Object,
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
        linea_id: this.event.linea_id,
        title: this.event.translations.filter(item =>item.locale === 'es')[0].title,
        title_en: this.event.translations.filter(item =>item.locale === 'en')[0].title,
        content: this.event.translations.filter(item =>item.locale === 'es')[0].content,
        content_en: this.event.translations.filter(item =>item.locale === 'en')[0].content,
        date_event: this.event.date_event,
        photo: "",
      }),
    };
  },
  methods: {
    update() {
      let data = {
        linea_id: this.form.linea_id,
        date_event: this.form.date_event,
        photo: this.form.photo,
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
      this.$inertia.post(route("event.update",this.event.id), data, {
        onSuccess: (page) => {},
        onError: (errors) => {
          console.log(errors);
        },
      });
      data={}
    },
  },
};
</script>
<style lang="css" scoped>
</style>
