<template>
    <AppLayout>
        <template #breadcrumb>
            <ItemCrumb title="Lineas" :active="true" link="line"></ItemCrumb>
            <ItemCrumb
                title="Testing imagenes"
                :active="true"
                link="event"
            ></ItemCrumb>
            <ItemCrumb title="Nuevo" :active="false" link=""></ItemCrumb>
        </template>
        <div
            class="my-4 md:w-3/4 mx-auto shadow flex flex-row flex-wrap border-gray-500 shadow-md p-5 justify-center md:space-x-2"
        >
            <template v-for="(item, index) in photos" :key="index">
                <div class="w-full md:w-1/4 m-2">
                    <image-option :image="item.url">
                        <template #left
                            ><i
                                @click="removePhoto(index)"
                                class="fa fa-trash-o fa-lg cursor-pointer"
                                aria-hidden="true"
                            ></i>
                        </template>
                    </image-option>
                </div>
            </template>
        </div>

        <form @submit.prevent="store">
            <div
                class="my-4 md:w-3/4 mx-auto shadow flex flex-col bg-white p-5"
            >
                <p class="text-center uppercase">
                    REGISTRO TESTING IMAGENES de la linea
                    <strong>{{ line.name }}</strong>
                </p>
                <JetValidationError></JetValidationError>

                <div class="flex flex-col md:flex-row md:space-x-2">
                    <div class="w-full">
                        <Label>Imagen</Label>
                        <JetInput
                            @change="showImage($event)"
                            type="file"
                            class="py-1"
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

                <div class="my-5">
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
import ItemCrumb from "@/components/ItemCrumb.vue";
import ImageOption from "../../../../components/lasaga/ImageOption.vue";

export default {
    props: {
        errors: Object,
        line: Object,
    },
    components: {
        AppLayout,
        InputError,
        Button,
        Label,
        JetInput,
        JetTextAreaVue,
        JetValidationError,
        ItemCrumb,
        ImageOption,
    },
    data() {
        return {
            photos: [],
            types: ["image/png", "image/jpeg"],
            form: this.$inertia.form({
                linea_id: this.line.id,
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
        showImage(ev) {
            if (ev.target.files.length > 0) {
                console.log(this.types.includes(ev.target.files[0].type));
                if (this.types.includes(ev.target.files[0].type)) {
                    let src = URL.createObjectURL(ev.target.files[0]);
                    this.photos.push({ url: src, file: ev.target.files[0] });
                } else {
                    cuteAlert({
                        type: "error",
                        title: "Error",
                        message: `${JSON.stringify(this.types)}`,
                        buttonText: "OK",
                    });
                }
            }
        },
        removePhoto(index) {
            this.photos = this.photos.filter((item, indx) => indx != index);
        },
        store() {
            let data = {
                linea_id: this.line.id,
                photo: this.photos.map((im) => im.file),
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
            this.$inertia.post(route("event.store"), data, {
                onSuccess: (page) => {},
                onError: (errors) => {
                    console.log(errors);
                },
            });
        },
    },
};
</script>
<style lang="css" scoped></style>
