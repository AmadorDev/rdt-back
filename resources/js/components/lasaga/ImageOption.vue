<template>
    <div class="relative shadow-sm border-indigo-500 border-2 h-48">
        <img class="w-full h-full" :src="image" />
        <div
            class="absolute top-0 bg-indigo-500 text-white p-2 w-10 hover:bg-indigo-800 text-center"
        >
            <slot name="left"></slot>
        </div>
        <template v-if="all">
            <div
                class="absolute right-0 top-0 bg-indigo-500 text-white p-2 w-10 hover:bg-indigo-800 text-center"
            >
                <slot name="right"></slot>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: {
        image: String,
        all: { type: Boolean, default: false },
    },
};
</script>

<style></style>
