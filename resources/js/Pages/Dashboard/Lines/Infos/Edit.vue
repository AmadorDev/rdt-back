<script setup>
import JetValidationError from "@/Jetstream/ValidationErrors.vue";
import JetInput from "@/Jetstream/Input.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Button from "@/Jetstream/Button";
import AppLayout from "@/Layouts/LayoutDashboard.vue";
import { Inertia } from '@inertiajs/inertia';
import ItemCrumb from '@/components/ItemCrumb.vue';
const props = defineProps({
    line: Object,
    status: String,
    hair_type:Object,
});

const form = useForm({
    content: props.hair_type?.translations.filter(item =>item.locale === 'es')[0].content,
    content_en: props.hair_type?.translations.filter(item =>item.locale === 'en')[0].content,
});
const store = () => {
    const data = {
        tranlations : [
        {
            content: form.content,
            locale: "es",
        },
        {
            content: form.content_en,
            locale: "en",
        },
    ]
    } ;
    Inertia.post(route("hair_type.update",props.hair_type?.id),data)
};
</script>

<template>
    <AppLayout>
        
        <template #breadcrumb>
            <ItemCrumb title="Lineas" :active="true" link="line"></ItemCrumb>
            <ItemCrumb
                title="Tipo de cabello"
                :active="true"
                link="hair_type"
            ></ItemCrumb>
            <ItemCrumb title="Editar" :active="false" link=""></ItemCrumb>
        </template>
        <form @submit.prevent="store">
            <div
                class="my-4 md:w-3/4 mx-auto shadow flex flex-col bg-white p-5"
            >
                <p class="text-center uppercase">
                    EDITAR TIPO DE CABELLO
                    
                </p>
                <JetValidationError></JetValidationError>

                <div class="flex flex-col md:flex-row md:space-x-2">
                    <div class="md:w-1/2">
                        <Label>Descripción </Label>
                        <JetInput v-model="form.content"></JetInput>
                    </div>
                    <div class="md:w-1/2">
                        <Label>Descripción en ingles</Label>
                        <JetInput v-model="form.content_en"></JetInput>
                    </div>
                </div>

                <div class="my-5">
                    <Button class="btn-sm">ACTUALIZAR</Button>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
