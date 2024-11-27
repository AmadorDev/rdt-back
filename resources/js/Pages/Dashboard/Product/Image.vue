<template>
    <AppLayout>
        <template #breadcrumb>
            <ItemCrumb
                title="Productos"
                :active="true"
                link="product"
            ></ItemCrumb>
            <ItemCrumb title="Imagenes" :active="false" link=""></ItemCrumb>
        </template>
        <p class="text-center font-lg py-5 text-lg">{{ product?.name }}</p>
        <div class="flex md:space-x-2 space-y-2 flex-wrap justify-center">
            <template v-for="(item, index) in images" :key="index">
                <div class="w-full md:w-1/4 m-2">
                    <image-option :image="item.url">
                        <template #left
                            ><i
                                @click="deleteImage(item.id)"
                                class="fa fa-trash-o fa-lg cursor-pointer"
                                aria-hidden="true"
                            ></i>
                        </template>
                    </image-option>
                </div>
            </template>
        </div>
        <form @submit.prevent="store" class="center mx-auto md:w-1/2">
            <div
                class="mt-5 flex space-x-2 border-2 shadow-md bg-white border-indigo-400 justify-around text-center p-5"
            >
                <div class="flex-1">
                    <input
                        class="form-control block w-full px-2 py-1 text-sm font-normal text-gray-700 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                        id="formFileSm"
                        type="file"
                        @input="form.photo = $event.target.files[0]"
                    />
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
import ImageCard from "@/components/lasaga/ImageCard.vue";
import ImageOption from '@/components/lasaga/ImageOption.vue';

export default {
    props: {
        errors: Object,
        images: Object,
        product: Object,
    },
    components: {
        AppLayout,
        InputError,
        Button,
        Label,
        JetInput,
        ItemCrumb,
        Badge,
        ImageCard,
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

            let config = { headers: { "Content-Type": "application/json" } };
            axios
                .post(
                    route("product_image.store", this.product.id),
                    formData,
                    config
                )
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

        uploadImage() {
            this.$refs.inputfile.click();
        },

        deleteImage(id) {
            Inertia.delete(route("product_image.destroy", id), {
                onSuccess: (page) => {},
                onError: (errors) => {
                    cuteAlert({
                        type: "error",
                        title: "Error",
                        message: JSON.stringify(errors),
                        buttonText: "OK",
                    });
                },
                onFinish: (visit) => {},
            });
        },

        setCover(id, val_origin, val) {
            if (val_origin === val) return;

            Inertia.put(
                route("line_image.cover", id),
                { cover: val, line: this.line },
                {
                    onSuccess: (page) => {},
                    onError: (errors) => {
                        cuteAlert({
                            type: "error",
                            title: "Error",
                            message: JSON.stringify(errors),
                            buttonText: "OK",
                        });
                    },
                    onFinish: (visit) => {},
                }
            );
        },
    },
};
</script>
<style lang="css" scoped></style>
