<template>
    <AppLayout>
        <template #breadcrumb>
      <ItemCrumb title="Categorias" :active="true" link="category"></ItemCrumb>
      <ItemCrumb title="Editar" :active="false" link=""></ItemCrumb>
    </template>
        <form @submit.prevent="update">
            <div class="my-4 w-3/4 mx-auto shadow flex flex-col space-y-5 bg-white p-5  ">
                <p class="text-center">REGISTRO CATEGORIA</p>
                <div class="">
                    <label for="title" class="text-gray-700 text-xs sm:text-md">Categoria</label>
                    <input name="title" type="text" class="
                    text-gray-600
                    w-full
                    h-4
                    sm:h-9
                    border-b-2 border-gray-300
                    focus:border-blue-300
                    outline-none
                  " v-model="form.name" />
                    <InputError :message="errors.name" />
                </div>
                <div class="">
                    <label for="title" class="text-gray-700 text-xs sm:text-md">Descripción</label>
                    <input name="title" type="text" class="
                    text-gray-600
                    w-full
                    h-4
                    sm:h-9
                    border-b-2 border-gray-300
                    focus:border-blue-300
                    outline-none
                  " v-model="form.description" />
                    <InputError :message="errors.description" />
                </div>
                <div>
                    <Button class='btn-sm uppercase'>Actualizar</Button>
                </div>
            </div>
        </form>
    </AppLayout>
    
</template>

<script>
import InputError from "@/Jetstream/InputError";
import Button from "@/Jetstream/Button";
import AppLayout from '@/Layouts/LayoutDashboard.vue'
import ItemCrumb from '@/components/ItemCrumb.vue';
export default {

    props: {
        errors: Object,
        category:Object,
    },
    components: {
        AppLayout,
        InputError,
        Button,ItemCrumb
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.category.name,
                description: this.category.description,
            }),

        };
    },
    methods: {
        update() {
            this.$inertia.put(route('category.update',this.category.id), this.form, {
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
