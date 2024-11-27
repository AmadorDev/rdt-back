<script setup>
import AppLayout from "@/Layouts/LayoutDashboard.vue";
import Paginate from "@/Shared/Paginate.vue";
import { useForm, Link } from "@inertiajs/inertia-vue3";
import Badge from "@/components/Badge.vue";
import { ref } from "vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import JetButton from '@/Jetstream/Button.vue';
import { Inertia } from '@inertiajs/inertia';
const props = defineProps({
    hair_types: Object,
    status: String,
    filters: Object,
});

const form = useForm({
    q: props.filter?.q,
});

const showConfirmation = ref(false);
const hair = ref("");

function confirmed(item) {
    hair.value = item;

    showConfirmation.value = true;
}
const destroy = async()=>{
   Inertia.delete(route("hair_type.destroy", hair.value?.id), {
        onError: (errors) => {
          cuteAlert({
            type: "error",
            title: "Error",
            message: JSON.stringify(errors),
            buttonText: "OK",
          });
        },
        onSuccess: (page) => {
          cuteToast({
            type: "success",
            title: "",
            message: "eliminado correctamente!",
            timer: 3000,
          });
        },
      });
      showConfirmation.value = false;
      hair.value = null;

}
</script>

<template>
    <AppLayout>
        {{hair}}
        <div class="pb-4 rounded-md w-full bg-white shadow-md p-5">
            <div class="flex justify-between w-full">
                <p class="py-2">Tipo de cabellos ({{ hair_types?.total }})</p>
            </div>
            <!-- search -->
            <div class="w-full flex">
                <div class="w-full inline-block relative">
                    <input
                        v-model="form.q"
                        type="text"
                        name=""
                        class="w-full py-1 px-8 rounded-lg"
                        placeholder="Buscar"
                    />

                    <div
                        class="pointer-events-none absolute pl-3 inset-y-0 left-0 flex items-center px-2 text-gray-400"
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
                                Titulo
                            </th>
                            <th
                                class="px-4 py-2 bg-gray-500"
                                style="background-color: #f8f8f8"
                            >
                                Linea
                            </th>

                            <th
                                class="px-4 py-2 text-center"
                                style="background-color: #f8f8f8"
                            >
                                acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-normal text-gray-700">
                        <template
                            v-for="(item, index) in hair_types?.data"
                            :key="index"
                        >
                            <tr
                                class="hover:bg-gray-100 border-b border-gray-200 py-2"
                            >
                                <td class="px-4 py-2">{{ item.content }}</td>
                                <td class="px-4 py-2">{{ item.line }}</td>

                                <td
                                    class="px-4 py-2 flex items-center justify-center space-x-2"
                                >
                                    <Link
                                        :href="route('hair_type.edit', item.id)"
                                    >
                                        <Badge class="bg-gray-600 text-white">
                                            <i
                                                class="fa fa-pencil-square-o fa-lg cursor-pointer"
                                                aria-hidden="true"
                                            ></i>
                                        </Badge>
                                    </Link>
                                    <Badge
                                        @click="confirmed(item)"
                                        class="bg-gray-600 text-white"
                                    >
                                        <i
                                            class="fa fa-trash-o fa-lg cursor-pointer"
                                            aria-hidden="true"
                                        ></i>
                                    </Badge>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <Paginate
                    :links="hair_types.links"
                    :total="hair_types.total"
                ></Paginate>
            </div>
        </div>

        <JetConfirmationModal :show="showConfirmation">
            <template #title> Alerta! </template>
            <template #content>
                Estas seguro de eliminar {{ hair.content }} ?
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
