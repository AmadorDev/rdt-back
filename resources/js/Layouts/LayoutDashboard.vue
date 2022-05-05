<template>
  <div
    x-data="{ isOpen: false }"
    class="md:flex min-h-screen bg-gray-200 relative text-sm antialiased"
  >
    <!-- sidebar -->

    <div
      class="
        sidebarNav
        fixed
        inset-y-0
        left-0
        w-64
        bg-white
        md:relative
        overflow-x-scroll
        text-gray-800
        flex-shrink-0
        z-30
        transform
        transition-all
        ease-in-out
        duration-200
        md:transform-none
        shadow-lg
        md:shadow-none
      "
      :class="{ 'translate-x-0': isOpen, '-translate-x-full': !isOpen }"
    >
      <!-- brand -->
      <div class="relative flex items-center">
        <a href="#" class="mx-6 my-4 block text-lg font-bold">RADIANT</a>
        <button
          @click="show"
          class="
            btnOpenSidebar
            absolute
            top-0
            right-0
            flex
            items-center
            justify-center
            p-3
            mx-2
            my-2
            rounded-full
            hover:bg-gray-100
            focus:outline-none
            transition-color
            duration-200
            block
            md:hidden
          "
        >
          <svg
            class="w-5 h-5 text-indigo-600"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            ></path>
          </svg>
        </button>
      </div>

      <!-- navigations -->
      <Sidebar></Sidebar>
    </div>

    <!-- top bar -->
    <div class="bg-gray-50 w-full flex-1">
      <header class="py-2 bg-white shadow-md">
        <div
          class="
            container
            flex
            justify-between
            items-center
            px-6
            mx-auto
            h-full
            space-x-2
          "
        >
          <!-- button responsive -->
          <button
            @click="show"
            class="
              btnOpenSidebar
              focus:bg-gray-100
              hover:bg-gray-100
              p-2
              rounded-full
              transition
              focus:outline-none
              md:hidden
              block
            "
          >
            <svg
              class="w-5 h-5 text-indigo-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
              ></path>
            </svg>
          </button>
          <div class="flex flex-1 lg:mr-32 justify-center relative">
            <div class="max-w-xl w-full relative mr-6">
              <div class="absolute inset-y-0 left-2 flex items-center">
                <svg
                  class="w-4 h-4 text-indigo-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  ></path>
                </svg>
              </div>
              <input
                class="
                  w-full
                  border-0
                  focus:border-0
                  bg-gray-100
                  focus:bg-white focus:ring-4 focus:ring-indigo-200
                  rounded-lg
                  py-2
                  pl-8
                  pr-3
                  text-sm
                  transition
                  duration-200
                "
                type="text"
                placeholder="Search"
              />
            </div>
          </div>
          <div class="flex justify-center items-center space-x-2">
            <!-- <a
              href="#"
              class="
                hover:bg-gray-100
                focus:bg-gray-100
                p-2
                rounded-full
                transition
              "
            >
              <svg
                class="w-5 h-5 text-indigo-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                ></path>
              </svg>
            </a> -->

            <!-- Settings Dropdown -->
            <div class="ml-3 relative">
              <jet-dropdown align="right" width="48">
                <template #trigger>
                  <span class="inline-flex rounded-md">
                    <button
                      type="button"
                      class="
                        inline-flex
                        items-center
                        px-3
                        py-2
                        border border-transparent
                        text-sm
                        leading-4
                        font-medium
                        rounded-md
                        text-gray-500
                        bg-white
                        hover:text-gray-700
                        focus:outline-none
                        transition
                      "
                    >
                      {{ $page.props.user.name }}

                      <svg
                        class="ml-2 -mr-0.5 h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        />
                      </svg>
                    </button>
                  </span>
                </template>

                <template #content>
                  <!-- Account Management -->
                  <div class="block px-4 py-2 text-xs text-gray-400">
                    Administrar cuenta
                  </div>

                  <jet-dropdown-link :href="route('profile.show')">
                    Perfil
                  </jet-dropdown-link>

                  <div class="border-t border-gray-100"></div>

                  <!-- Authentication -->
                  <form @submit.prevent="logout">
                    <jet-dropdown-link as="button">
                      Cerrar session
                    </jet-dropdown-link>
                  </form>
                </template>
              </jet-dropdown>
            </div>
          </div>
        </div>
      </header>

      <main>
        <div class="container mx-auto p-3 bg-gray-200 min-h-screen">
          <Breadcrumb><slot name="breadcrumb"></slot></Breadcrumb>
          
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>
  

<script>
import Sidebar from "./Sidebar.vue";
import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import Breadcrumb from '../components/Breadcrumb.vue';
import ItemCrumb from '@/components/ItemCrumb.vue';

export default {
  components: { Sidebar, JetDropdown, JetDropdownLink, Breadcrumb,ItemCrumb },
  methods: {
    show() {
      const sidebarNav = document.querySelector(".sidebarNav");
      sidebarNav.classList.toggle("-translate-x-full");
    },

    logout() {
      this.$inertia.post(route("logout"));
    },
  },
  updated() {},
};
</script>

<style>
</style>