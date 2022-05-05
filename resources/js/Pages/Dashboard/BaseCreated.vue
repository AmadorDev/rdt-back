<template>
    <AppLayout>
        <form @submit.prevent="store">
            <div class="my-4 w-3/4 mx-auto shadow flex flex-col space-y-5 bg-white p-5  ">
                <p class="text-center">REGISTRO LINEA</p>
                <div class="">
                    <Label>Categoria</Label>
                    <JetInput v-model="form.name"></JetInput>
                    <InputError :message="errors.name" />
                </div>
               
                <div>
                    <Button class='btn-sm'>REGISTRAR</Button>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
<script>
import InputError from "@/Jetstream/InputError";
import Button from "@/Jetstream/Button";

import AppLayout from '@/Layouts/LayoutDashboard.vue'
import Label from '@/Jetstream/Label';
import JetInput from '@/Jetstream/Input.vue';

export default {

    props: {
        errors: Object,
    },
    components: {
        AppLayout,
        InputError,
        Button,
        Label,
        JetInput
    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                description: '',
            }),

        };
    },
    methods: {
        store() {
            this.$inertia.post("/categories", this.form, {
                onSuccess: (page) => {  
                },
                onError: (errors) => {
                    console.log(errors);
                },
            });
        },

    }



}

</script>
<style lang="css" scoped>
</style>
