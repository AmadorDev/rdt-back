<template>
  <AppLayout>
    <div class="pb-4 rounded-md w-full bg-white shadow-md p-5">
      <div class="flex justify-between w-full">
        <p class="py-2">Eventos de Lineas ({{ events?.total }})</p>
      </div>
      <!-- search -->
      <div class="w-full flex">
        <div class="w-full inline-block relative">
          <input
            v-model="form.search"
            type="text"
            name=""
            class="w-full py-1 px-8 rounded-lg"
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
              text-gray-400
            "
          >
            <i class="fa fa-search" aria-hidden="true"></i>
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
                Linea
              </th>
              <th class="px-4 py-2" style="background-color: #f8f8f8">
                Descripción
              </th>

              <th class="px-4 py-2 text-center" style="background-color: #f8f8f8">acciones</th>
            </tr>
          </thead>
          <tbody class="text-sm font-normal text-gray-700">
            <template v-for="(item, index) in events?.data" :key="index">
              <tr class="hover:bg-gray-100 border-b border-gray-200 py-2">
                <td class="px-4 py-2">{{ item.title }}</td>
                <td class="px-4 py-2">{{ item.content.slice(0, 100) }}...</td>

                <td class="px-4 py-2 flex items-center justify-center space-x-2">
                  

                   <Badge class="bg-gray-600 text-white"  @click="confirmed(item)">
                     <i
                    class="fa fa-trash-o fa-lg cursor-pointer"
                    
                    aria-hidden="true"
                  ></i>
                    </Badge>

                  
                  <Badge class="bg-gray-600 text-white"  @click="showDialog(item.url)">
                     <i
                    class="fa fa-picture-o fa-lg "
                   
                    aria-hidden="true"
                  ></i>
                    </Badge>

                  <Link :href="route('product_event.edit', item.id)">
                    <Badge class="bg-gray-600 text-white">
                      <i
                        class="fa fa-pencil-square-o fa-lg "
                        aria-hidden="true"
                      ></i>
                    </Badge>
                  </Link>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
        <Paginate :links="events.links" :total="events.total"></Paginate>
      </div>
    </div>
    <JetDialogModal :show="modal" @close="() => (modal = false)" max-width="md">
      <template #content>
        <img :src="event" alt="" srcset="" />
      </template>
    </JetDialogModal>

    <JetConfirmationModal :show="showConfirmation">
      <template #title> Alerta! </template>
      <template #content>
        Estas seguro de eliminar {{ event.title }} ?
      </template>
      <template #footer>
        <div class="space-x-2">
          <JetButton
            @click="showConfirmation = false"
            class="bg-gray-800 hover:bg-gray-600"
            type="button"
          >
            Cancelar</JetButton
          >
          <JetButton
            @click="destroy()"
            type="button"
            class="bg-red-500 hover:bg-red-400"
          >
            Eliminar</JetButton
          >
        </div>
      </template>
    </JetConfirmationModal>
  </AppLayout>
  {{ event }}
</template>

<script>
import AppLayout from "@/Layouts/LayoutDashboard.vue";
import Paginate from "@/Shared/Paginate.vue";
import { throttle } from "lodash/";
import { Link } from "@inertiajs/inertia-vue3";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import Badge from "@/components/Badge.vue";

export default {
  props: {
    errors: Object,
    filters: Object,
    events: Object,
  },

  components: {
    Paginate,
    AppLayout,
    Link,
    JetDialogModal,
    JetConfirmationModal,
    JetButton,
    Badge,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
      showConfirmation: false,
      event: null,
      modal: false,
    };
  },
  methods: {
    showDialog(url) {
      this.modal = true;
      this.event = url;
    },
    confirmed(item) {
      this.event = item;
      this.showConfirmation = true;
    },
    destroy() {
      this.$inertia.delete(route("product_event.destroy", this.event.id), {
        onError: (errors) => {
          cuteAlert({
            type: "error",
            title: "Error",
            message: JSON.stringify(errors),
            buttonText: "OK",
          });
        },
        onSuccess:(page)=>{
          cuteToast({
            type: "success",
            title: "",
            message: "eliminado correctamente!",
            timer: 3000,
          });
        }
      });
      this.showConfirmation = false;
      this.event = null;
    },
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route("product_event"), this.form, {
          preserveState: true,
        });
      }, 250),
    },
  },
};
</script>

<style>
</style>