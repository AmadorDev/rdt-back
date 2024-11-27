<template>
    <AppLayout>
        <template #breadcrumb>
            <ItemCrumb title="Lineas" :active="true" link="line"></ItemCrumb>
            <ItemCrumb title="Imagenes" :active="false" link=""></ItemCrumb>
        </template>
        <div
            class="flex md:flex-row md:space-x-2 space-y-2 flex-wrap justify-center"
        >
            <template v-for="(item, index) in images" :key="index">
                <div class="w-full md:w-1/4 m-2">
                    <image-option :image="item.url" :all="true">
                        <template #left
                            ><i
                                @click="deleteImage(item.id)"
                                class="fa fa-trash-o fa-lg cursor-pointer"
                                aria-hidden="true"
                            ></i>
                        </template>
                        <template #right>
                            <template v-if="item.cover">
                                <i
                                    @click="setCover(item.id, item.cover, 0)"
                                    class="fa fa-circle fa-lg cursor-pointer"
                                    aria-hidden="true"
                                ></i>
                            </template>
                            <template v-else>
                                <i
                                    @click="setCover(item.id, item.cover, 1)"
                                    class="fa fa-circle-thin fa-lg cursor-pointer"
                                    aria-hidden="true"
                                ></i>
                            </template>
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
import ImageOption from "@/components/lasaga/ImageOption.vue";

export default {
    props: {
        errors: Object,
        images: Object,
        line: Number,
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
                linea_id: this.line,
            }),
        };
    },
    methods: {
        store() {
            if (!this.form.photo) return;
            let formData = new FormData();
            formData.append("photo[0]", this.form.photo);
            formData.append("linea_id", this.form.linea_id);

            let config = { headers: { "Content-Type": "application/json" } };
            axios
                .post("/api/v1/lineas/files", formData, config)
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
            Inertia.delete(route("line.image.destroy", id), {
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
