<template>
    <AppLayout>

        <p class="text-center font-lg py-5 text-lg">Imagenes del evento </p>
        <div class="flex md:flex-row md:space-x-2 space-y-2 flex-wrap justify-center">

            <template v-for="(item, index) in images" :key="index">
                <div class="w-full md:w-1/4 m-2">
                    <image-option :image="item.url">
                        <template #left><i @click="deleteImage(item.id)" class="fa fa-trash-o fa-lg cursor-pointer"
                                aria-hidden="true"></i>
                        </template>
                    </image-option>
                    
                </div>
            </template>
        </div>
        <form @submit.prevent="store" class="center mx-auto md:w-1/2">
            <div class="mt-5 flex space-x-2 border-2 shadow-md bg-white border-indigo-400 justify-around text-center p-5">
                <div class="flex-1">
                    <input
                        class="form-control block w-full px-2 py-1 text-sm font-normal text-gray-700 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                        id="formFileSm" type="file" @change="(e) => uploadFiles(e)" accept=".jpg, .jpeg, .png" />
                </div>
                <div class="flex-1 text-right">
                    <Button>Guardar</Button>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
<script>
import { Inertia } from "@inertiajs/inertia";
import InputError from "@/Jetstream/InputError";
import Button from "@/Jetstream/Button";

import AppLayout from "@/Layouts/LayoutDashboard.vue";
import Label from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input.vue";
import ItemCrumb from "@/components/ItemCrumb.vue";
import Badge from "@/components/Badge.vue";

import ImageOption from "@/components/lasaga/ImageOption.vue";

export default {
    props: {
        errors: Object,
        images: Object,
        event_id: Number,
        SIZE_FILE_NEW:Number
    },
    components: {
        AppLayout,
        InputError,
        Button,
        Label,
        JetInput,
        ItemCrumb,
        Badge,
        ImageOption,
    },
    data() {
        return {
            form: this.$inertia.form({
                photo: null,
            }),
        };
    },
    methods: {

        store() {
            if (!this.form.photo) return;
            let formData = new FormData();
            formData.append("photo[0]", this.form.photo);
            formData.append("event_id", this.event_id);

            let config = { headers: { "Content-Type": "application/json" } };
            axios
                .post(route("new_event_images.store"), formData, config)
                .then((response) => {
                    Inertia.reload({ only: ["images"] });
                    this.form.photo = "";
                    document.getElementById("formFileSm").value = "";
                    if (response.data.error) {
                        cuteAlert({
                            type: "error",
                            title: "Error",
                            message: JSON.stringify(response.data.error),
                            buttonText: "OK",
                        });
                    }
                })
                .catch((e) => {
                    cuteAlert({
                        type: "error",
                        title: "Error",
                        message: JSON.stringify(e),
                        buttonText: "OK",
                    });
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
                    document.getElementById("formFileSm").value = "";
                }
            }
        },

        deleteImage(id) {
            Inertia.delete(route("new_event_images.destroy", id), {
                onSuccess: (page) => {

                    cuteAlert({
                        type: "success",
                        title: "Error",
                        message: "eliminado correctamente",
                        buttonText: "OK",
                    });
                 },
                onError: (errors) => {
                    cuteAlert({
                        type: "error",
                        title: "Error",
                        message: JSON.stringify(errors),
                        buttonText: "OK",
                    });
                },
                onFinish: (visit) => { },
            });
        },
    },
};
</script>
<style lang="css" scoped></style>
