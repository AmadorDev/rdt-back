<template>
  <AppLayout>
    <div class="py-2">
      <div class="flex justify-end">
        <Link
          :href="route('salon.add')"
          class="
            bg-indigo-500
            rounded-full
            font-bold
            text-white
            px-4
            py-2
            transition
            duration-300
            ease-in-out
            hover:bg-indigo-600
          "
          ><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
          <span class="mx-1"> Nuevo</span>
        </Link>
      </div>
    </div>

    <div class="pb-4 rounded-md w-full bg-white shadow-md p-5">
      <div class="flex justify-between w-full">
        <p class="py-2">Salones ({{ salons?.total }})</p>
      </div>
      <!-- search -->
      <div class="w-full flex">
        <div class="w-full inline-block relative">
          <input
            v-model="form.search"
            type="text"
            name=""
            class="
              leading-snug
              border border-gray-700
              block
              w-full
              appearance-none
              bg-gray-100
              text-sm text-gray-700
              py-1
              px-8
              rounded-lg
            "
            placeholder="Buscar"
          />

          <div
            class="
              pointer-events-none
              absolute
              pl-3
              inset-y-0
              left-0
              flex
              items-center
              px-2
              text-gray-300
            "
          >
            <svg
              class="fill-current h-3 w-3"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 511.999 511.999"
            >
              <path
                d="M508.874 478.708L360.142 329.976c28.21-34.827 45.191-79.103 45.191-127.309C405.333 90.917 314.416 0 202.666 0S0 90.917 0 202.667s90.917 202.667 202.667 202.667c48.206 0 92.482-16.982 127.309-45.191l148.732 148.732c4.167 4.165 10.919 4.165 15.086 0l15.081-15.082c4.165-4.166 4.165-10.92-.001-15.085zM202.667 362.667c-88.229 0-160-71.771-160-160s71.771-160 160-160 160 71.771 160 160-71.771 160-160 160z"
              />
            </svg>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto mt-6">
        <table class="table-auto border-collapse w-full">
          <thead>
            <tr
              class="rounded-lg text-sm font-medium text-gray-700 uppercase text-left"
              style="font-size: 0.9674rem"
            >
              <th
                class="px-4 py-2 bg-gray-500"
                style="background-color: #f8f8f8"
              >
                Salon
              </th>
              <th class="px-4 py-2" style="background-color: #f8f8f8">
                Dirección
              </th>
              <th class="px-4 py-2" style="background-color: #f8f8f8">
                lat, lng
              </th>
              <th class="px-4 py-2 text-center" style="background-color: #f8f8f8">acciones</th>
            </tr>
          </thead>
          <tbody class="text-sm font-normal text-gray-700">
            <template v-for="(item, index) in salons?.data" :key="index">
              <tr class="hover:bg-gray-100 border-b border-gray-200 py-2">
                <td class="px-4 py-2">{{ item.name }}</td>
                <td class="px-4 py-2">{{ item.address }}</td>
                <td class="px-4 py-2">{{ item.lat }}  ,  {{ item.lng }}</td>

                <td class="px-4 py-2 flex justify-center items-center space-x-2">
                  <Badge
                    class="bg-gray-600 text-white"
                    @click="confirmed(item)"
                  >
                    <i
                      class="fa fa-trash-o fa-lg cursor-pointer"
                      aria-hidden="true"
                    ></i>
                  </Badge>
                  <Link :href="route('salon.edit', item.id)">
                    <Badge class="bg-gray-600 text-white">
                      <i
                        class="fa fa-pencil-square-o fa-lg cursor-pointer"
                        aria-hidden="true"
                      ></i>
                    </Badge>
                  </Link>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
        <Paginate :links="salons.links" :total="salons.total"></Paginate>
      </div>
    </div>
  </AppLayout>
  <JetConfirmationModal :show="showConfirmation">
    <template #title> Alerta! </template>
    <template #content> Estas seguro de Eliminar {{salon.name}}? </template>
    <template #footer>
      <Button
        @click="showConfirmation = false"
        class="bg-gray-800 hover:bg-gray-600"
        type="button"
      >
        Cancelar</Button
      >
      <Button
        @click="destroy()"
        type="button"
        class="bg-red-500 hover:bg-red-400 ml-2"
      >
        Eliminar</Button
      >
    </template>
  </JetConfirmationModal>
</template>
<script>
import AppLayout from "@/Layouts/LayoutDashboard.vue";

import { Link } from "@inertiajs/inertia-vue3";
import Button from "@/Jetstream/Button";

import { throttle } from "lodash/";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import Paginate from "../../../Shared/Paginate.vue";
import Badge from '@/components/Badge.vue';

export default {
  props: {
    errors: Object,
    salons: Object,
    filters: Object,
  },
  components: {
    AppLayout,
    Link,
    Button,
    JetDialogModal,
    JetConfirmationModal,
    Paginate,Badge
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
      showConfirmation: false,
      salon: null,
    };
  },
  methods: {
    formatDate(date) {
      //   return moment(new Date(date)).format("DD/MM/YYYY");
    },
    confirmed(item) {
      this.showConfirmation = true;
      this.salon = item;
    },

    destroy() {
      this.$inertia.delete(route("salon.destroy", this.salon.id), {
        onError: (errors) => {
          cuteAlert({
            type: "error",
            title: "Error",
            message: JSON.stringify(errors),
            buttonText: "OK",
          });
        },
        onSuccess: (page) => {
          cuteAlert({
            type: "success",
            title: "",
            message: "eliminado correctamente!",
            buttonText: "OK",
          });
        },
      });
      this.showConfirmation = false;
      this.salon = null;
    },
  },

  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route('salon'), this.form, {
          preserveState: true,
        });
      }, 250),
    },
  },
};
</script>
<style lang="css" scoped>
</style>
