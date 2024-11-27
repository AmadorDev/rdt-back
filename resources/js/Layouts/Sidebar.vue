<template>
  <!-- Contenedor -->
  <ul id="accordion" class="accordion">
    <li @click="ToggleLink($event)" :class="$page.url === '/dashboard' ? 'link-active' : ''">
      <Link :href="route('dashboard')">
      <div class="link">
        <i class="fa fa-home" aria-hidden="true"></i>

        Dashboard
      </div>
      </Link>
    </li>

    <!-- <li
      @click="ToggleLink($event)"
      :class="$page.url.startsWith('/news') ? 'open ' : ''"
    >
      <div class="link">
        <i class="fa fa-cogs"></i>Gestión Novedades
        <i class="fa fa-chevron-down"></i>
      </div>
      <ul
        class="submenu"
        :class="$page.url.startsWith('/news') ? 'show ' : ''"
      >
        <li :class="newsMenu.includes($page.url) ? 'link-active ' : ''">
          <Link :href="route('new')">Novedades</Link>
        </li>
        <li :class="$page.url.startsWith('/news/events') ? 'link-active ' : ''">
          <Link :href="route('new_event')">Eventos</Link>
        </li>
         <li :class="$page.url.startsWith('/news/galleries') ? 'link-active ' : ''">
          <Link :href="route('new_galery')">Galerias</Link>
        </li>
        <li :class="$page.url.startsWith('/news/latests') ? 'link-active ' : ''">
          <Link :href="route('new_latest')">Últimos lanzamientos</Link>
        </li>
      
      </ul>
    </li> -->


    <li @click="ToggleLink($event)" :class="$page.url.startsWith('/salons') ? 'link-active' : ''">
      <Link :href="route('salon')">
      <div class="link">
        <i class="fa fa-tasks" aria-hidden="true"></i>

        Salones
      </div>
      </Link>
    </li>
    <li @click="ToggleLink($event)" :class="$page.url.startsWith('/banners') ? 'link-active' : ''">
      <Link :href="route('banner')">
      <div class="link">
        <i class="fa fa-tasks" aria-hidden="true"></i>
        Banners
      </div>
      </Link>
    </li>
    <li @click="ToggleLink($event)" :class="$page.url.startsWith('/results') ? 'link-active' : ''">
      <Link :href="route('result')">
      <div class="link">
        <i class="fa fa-tasks" aria-hidden="true"></i>
        Resultados
      </div>
      </Link>
    </li>
    <li @click="ToggleLink($event)" :class="$page.url.startsWith('/lines') ? 'link-active' : ''">
      <Link :href="route('line')">
      <div class="link">
        <i class="fa fa-tasks" aria-hidden="true"></i>
        Categorias
      </div>
      </Link>
    </li>

    <li @click="ToggleLink($event)" :class="$page.url.startsWith('/products/lines') ? 'link-active' : ''">
      <Link :href="route('product')">
      <div class="link">
        <i class="fa fa-tasks" aria-hidden="true"></i>
        Productos de linea
      </div>
      </Link>
    </li>
    <li @click="ToggleLink($event)" :class="$page.url.startsWith('/products/cabs') ? 'link-active' : ''">
      <Link :href="route('cab')">
      <div class="link">
        <i class="fa fa-tasks" aria-hidden="true"></i>
        Productos de cabina
      </div>
      </Link>
    </li>





    <!-- <li
      @click="ToggleLink($event)"
      :class="$page.url.startsWith('/lines') ? 'open ' : ''"
    >
      <div class="link">
        <i class="fa fa-cogs"></i>Gestión lineas
        <i class="fa fa-chevron-down"></i>
      </div>
      <ul
        class="submenu"
        :class="$page.url.startsWith('/lines') ? 'show ' : ''"
      >
        <li :class="linesMenu.includes($page.url) ? 'link-active ' : ''">
          <Link :href="route('line')">Lineas</Link>
        </li>
         <li :class="$page.url.startsWith('/lines/events') ? 'link-active ' : ''">
          <Link :href="route('event')">Testing Imagen</Link>
        </li> 
         <li :class="$page.url.startsWith('/lines/videos') ? 'link-active ' : ''">
          <Link :href="route('video')">Testing videos</Link>
        </li>
        <li :class="$page.url.startsWith('/lines/hair-type') ? 'link-active ' : ''">
          <Link :href="route('hair_type')">Tipo de cabello</Link>
        </li>
      </ul>
    </li> -->

    <!-- <li
      @click="ToggleLink($event)"
      :class="$page.url.startsWith('/product') ? 'open ' : ''"
    >
      <div class="link">
        <i class="fa fa-cogs"></i>Gestión Productos
        <i class="fa fa-chevron-down"></i>
      </div>
      <ul
        class="submenu"
        :class="$page.url.startsWith('/product') ? 'show ' : ''"
      >
        <li :class="productMenu.includes($page.url) ? 'link-active ' : ''">
          <Link href="/products">Productos</Link>
        </li> 
         <li :class="$page.url.startsWith('/products/events') ? 'link-active ' : ''">
          <Link :href="route('product_event')">Eventos</Link>
        </li>
        <li :class="$page.url.startsWith('/products/videos') ? 'link-active ' : ''">
          <Link :href="route('product_video')">Videos</Link>
        </li> 
      </ul>
    </li> -->
  </ul>

</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },
  data() {
    return {
      newsMenu: [
        `${!this.$page.url.includes("/news/events") &&
          !this.$page.url.includes("/news/galleries") &&
          !this.$page.url.includes("/news/latest") &&
          this.$page.url.includes("/news")
          ? this.$page.url
          : undefined
        }`,
      ],
      linesMenu: [
        `${!this.$page.url.includes("/lines/hair-type") &&
          !this.$page.url.includes("/lines/events") &&
          !this.$page.url.includes("/lines/video") &&
          this.$page.url.includes("/lines")
          ? this.$page.url
          : undefined
        }`,
      ],
      productMenu: [
        `${!this.$page.url.includes("/products/events") &&
          !this.$page.url.includes("/products/videos") &&
          this.$page.url.includes("products")
          ? this.$page.url
          : undefined
        }`,
      ],



    };
  },
  methods: {
    ToggleLink(e) {
      let parent = e.target.parentNode;
      try {
        if (parent.classList.contains("open")) {
          parent.classList.remove("open");
          for (let i = 0; i < parent.childNodes.length; i++) {
            if (parent.childNodes[i].classList.contains("submenu")) {
              parent.childNodes[i].classList.remove("show");
            }
          }
        } else {
          parent.classList.add("open");
          for (var i = 0; i < parent.childNodes.length; i++) {
            if (parent.childNodes[i].classList.contains("submenu")) {
              parent.childNodes[i].classList.add("show");
            }
          }
        }
      } catch (error) {
        console.log(error);
        return;
      }
    },
  },
};
</script>

<style></style>