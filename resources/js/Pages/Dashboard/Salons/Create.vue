<template>
  <AppLayout>
    <template #breadcrumb>
      <ItemCrumb title="Salones" :active="true" link="salon"></ItemCrumb>
      <ItemCrumb title="Nuevo" :active="false" link="salon"></ItemCrumb>
    </template>

    <form @submit.prevent="store">
      <div
        class="my-4 w-3/4 mx-auto shadow flex flex-col space-y-5 bg-white p-5"
      >
        <JetValidationError></JetValidationError>
        <p class="text-center uppercase">REGISTRO salon</p>
        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="flex-1">
            <Label>Nombre</Label>
            <JetInput v-model="form.name"></JetInput>
          </div>
          <div class="flex-1">
            <Label>Distrito</Label>
            <JetInput v-model="form.district"></JetInput>
          </div>
        </div>
        <div class="flex flex-col md:flex-row md:space-x-2">
          <div class="flex-1">
            <Label>Ciudad</Label>
            <JetInput v-model="form.city"></JetInput>
          </div>
          <div class="flex-1">
            <Label>País</Label>
            <JetInput v-model="form.country"></JetInput>
          </div>
        </div>
        <div class="">
          <Label>Dirección</Label>
          <JetInput v-model="form.address"></JetInput>
        </div>

        <div class="flex md:flex-row md:space-x-2">
          <div class="flex-1">
            <Label>Latitud</Label>
            <JetInput v-model="form.lat"></JetInput>
          </div>
          <div class="flex-1">
            <Label>Longitud</Label>
            <JetInput v-model="form.lng"></JetInput>
          </div>
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
import JetInput from "@/Jetstream/Input.vue";
import Label from "@/Jetstream/Label";
import JetValidationError from "@/Jetstream/ValidationErrors.vue";
import ItemCrumb from "@/components/ItemCrumb.vue";

export default {
  props: {
    errors: Object,
  },
  components: {
    AppLayout,
    InputError,
    Button,
    JetInput,
    Label,
    JetValidationError,
    ItemCrumb,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: "",
        district: "",
        city: '',
        address: "",
        country: "PERÚ",
        lat: "",
        lng: "",
      }),
    };
  },
  methods: {
    store() {
      this.$inertia.post(route("salon.store"), this.form, {
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
