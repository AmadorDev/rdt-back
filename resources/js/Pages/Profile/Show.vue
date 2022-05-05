<template>
  <app-layout>
    <div v-if="$page.props.jetstream.canUpdateProfileInformation">
      <update-profile-information-form :user="$page.props.user" />

      <jet-section-border />
    </div>
    <div>
      <div v-if="$page.props.jetstream.canUpdatePassword">
        <update-password-form class="mt-10 sm:mt-0" />

        <jet-section-border />
      </div>

      <logout-other-browser-sessions-form
        :sessions="sessions"
        class="mt-10 sm:mt-0"
      />
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/LayoutDashboard.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import TwoFactorAuthenticationForm from "@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";

export default defineComponent({
  props: ["sessions"],

  components: {
    AppLayout,
    DeleteUserForm,
    JetSectionBorder,
    LogoutOtherBrowserSessionsForm,
    TwoFactorAuthenticationForm,
    UpdatePasswordForm,
    UpdateProfileInformationForm,
  },
});
</script>
