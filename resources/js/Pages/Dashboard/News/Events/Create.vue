<template>
  <AppLayout>
    <template #breadcrumb>
      <ItemCrumb title="Eventos" :active="true" link="new_event"></ItemCrumb>

      <ItemCrumb title="Nuevo" :active="false" link=""></ItemCrumb>
    </template>
    <form @submit.prevent="store">
      <div class="my-4 md:w-3/4 mx-auto shadow flex flex-col bg-white p-5">
        <p class="text-center uppercase">REGISTRO EVENTO</p>
        <JetValidationError></JetValidationError>

        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="md:w-1/2">
            <Label>Fecha del evento </Label>
            <JetInput v-model="form.date_event" type="date"></JetInput>
          </div>
          <div class="md:w-1/2">
            <Label>Imagen</Label>
            <JetInput type="file" class="py-1" @change="(e) => uploadFiles(e)" accept=".jpg, .jpeg, .png"></JetInput>
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
    SIZE_FILE_NEW: Number,
  },
  components: {
    AppLayout,
    InputError,
    Button,
    Label,
    JetInput,
    JetTextAreaVue,
    JetValidationError, ItemCrumb
  },
  data() {
    return {
      form: this.$inertia.form({
        title: "",
        title_en: "",
        content: "",
        content_en: "",
        date_event: "",
        photo: "",
      }),


    };
  },
  methods: {
    store() {
      let data = {

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
      this.$inertia.post(route("new_event.store"), data, {
        onSuccess: (page) => { },
        onError: (errors) => {
          console.log(errors);
        },
      });
    },

    uploadFiles(e) {

      let typesFile = ["application/pdf", "image/png", "image/jpg", "image/jpeg"];
      let sizeFile = parseInt(this.SIZE_FILE_NEW / 1048576)//byte
      if (e.target.files[0] != undefined) {
        if (

          e.target.files[0].size < this.SIZE_FILE_NEW &&
          typesFile.includes(e.target.files[0].type)
        ) {

          this.form.photo = e.target.files[0];
        } else {
          cuteToast({
            type: "error",
            title: "",
            message: `Tamaño de archivo max ${sizeFile} mb, ${typesFile.toString()}`,
            timer: 3000,
          });
          this.form.photo = "";
          
        }
      }
    }

  },
};
</script>
<style lang="css" scoped></style>
