<template>
  <AppLayout>
    <div class="py-2">
      <div class="flex justify-end">
        <Link
          :href="route('cab.add')"
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
            shadow-md
          "
        >
          <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
          <span class="mx-1"> Nuevo</span>
        </Link>
      </div>
    </div>

    <div class="pb-4 rounded-md w-full bg-white shadow-md p-5">
      <div class="flex justify-between w-full">
        <p class="py-2">Productos ({{ products?.total }})</p>
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
            <i class="fa fa-search" aria-hidden="true" ></i>
          </div>
        </div>
      </div>
      <div class="overflow-x-auto mt-6">
        <table class="table-auto border-collapse w-full">
          <thead>
            <tr
              class="
                rounded-lg
                text-sm
                font-medium
                text-gray-700
                uppercase
                text-left
              "
              style="font-size: 0.9674rem"
            >
              <th
                class="px-4 py-2 bg-gray-500"
                style="background-color: #f8f8f8"
              >
                Producto 
              </th>
              <th
                class="px-4 py-2 bg-gray-500"
                style="background-color: #f8f8f8"
              >
                Cabina
              </th>
              <th
                class="px-4 py-2 space-x-3 text-center"
                style="background-color: #f8f8f8"
              >
                
              </th>
            </tr>
          </thead>
          <tbody class="text-sm font-normal text-gray-700">
            <template v-for="(item, index) in products?.data" :key="index">
              <tr class="hover:bg-gray-100 border-b border-gray-200 py-2">
                <td class="px-4 py-2">{{ item.name }} {{ item.id }}</td>
                <td class="px-4 py-2">{{ item.line?.name ?? '' }}</td>
                <td
                  class="
                    px-4
                    py-2
                    flex flex-col
                    md:flex-row
                    items-center
                    justify-center
                    content-center
                    md:space-x-2
                  "
                >
                  <Link :href="route('cab.image', item.id)">
                    <Badge class="bg-gray-600 text-white">
                      <i
                        class="fa fa-picture-o fa-lg cursor-pointer"
                        aria-hidden="true"
                      ></i>
                    </Badge>
                  </Link>

                  <Link :href="route('cab.edit', item.id)">
                    <Badge class="bg-gray-600 text-white">
                      <i
                        class="fa fa-pencil-square-o fa-lg cursor-pointer"
                        aria-hidden="true"
                      ></i
                    ></Badge>
                  </Link>
                  <Badge
                    class="bg-gray-600 text-white"
                    @click="confirmed(item)"
                  >
                    <i
                      class="fa fa-trash-o fa-lg cursor-pointer"
                      aria-hidden="true"
                    ></i>
                  </Badge>

                  <!-- <Link :href="route('product_event.add', item.id)">
                    <Badge class="bg-gray-300">
                      <i class="fa fa-plus" aria-hidden="true"></i>
                      <i
                        class="fa fa-calendar-o fa-lg cursor-pointer"
                        aria-hidden="true"
                      ></i
                    ></Badge>
                  </Link>
                  <Link :href="route('product_video.add', item.id)">
                    <Badge class="bg-gray-300" click="confirmed(item)">
                      <i class="fa fa-plus" aria-hidden="true"></i>

                      <i
                        class="fa fa-video-camera fa-lg cursor-pointer"
                        aria-hidden="true"
                      ></i
                    ></Badge>
                  </Link> -->
                </td>
              </tr>
            </template>
          </tbody>
        </table>
        <Paginate :links="products?.links" :total="products?.total"></Paginate>
      </div>
    </div>

    <JetDialogModal :show="modal" @close="() => (modal = false)" max-width="md">
      <template #content>
        <img :src="product" alt="" srcset="" />
      </template>
    </JetDialogModal>
    <JetConfirmationModal :show="showConfirmation">
      <template #title> Alerta! </template>
      <template #content>
        Estas seguro de eliminar {{ product.name }} ?
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
</template>

<script>
import AppLayout from "@/Layouts/LayoutDashboard.vue";
import Paginate from "@/Shared/Paginate.vue";
import { throttle } from "lodash/";
import { Link } from "@inertiajs/inertia-vue3";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import Badge from "@/components/Badge.vue";

export default {
  props: {
    errors: Object,
    filters: Object,
    products: Object,
  },

  components: {
    Paginate,
    AppLayout,
    Link,
    JetConfirmationModal,
    JetButton,
    JetDialogModal,
    Badge,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
      showConfirmation: false,
      product: null,
      modal: false,
    };
  },
  methods: {
    confirmed(item) {
      this.showConfirmation = true;
      this.product = item;
    },
    async showDialog(id) {
      try {
        const rs = await axios.get(route("cab.image", id), {
          method: "GET",
        });
        this.modal = true;
        this.product = rs.data;
      } catch (error) {}
    },
    destroy() {
      this.$inertia.delete(route("cab.destroy", this.product.id), {
        onError: (errors) => {
          cuteAlert({
            type: "error",
            title: "Error",
            message: JSON.stringify(errors),
            buttonText: "OK",
          });
        },
      });
      this.showConfirmation = false;
      this.product = null;
    },
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get(route("cab"), this.form, {
          preserveState: true,
        });
      }, 250),
    },
  },
};
</script>

<style>
.dropdown:focus-within .dropdown-menu {
  opacity: 1;
  transform: translate(0) scale(1);
  visibility: visible;
}
</style>