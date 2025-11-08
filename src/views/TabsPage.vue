<template>
  <ion-page>
    <ion-tabs>
      <ion-router-outlet :key="route.fullPath"></ion-router-outlet>
      <ion-tab-bar slot="bottom">

        <ion-tab-button tab="tab2" href="/tabs/tab1">
          <ion-icon aria-hidden="true" :icon="logInOutline" />
          <ion-label>Login Page</ion-label>
        </ion-tab-button>

        <ion-tab-button tab="tab3" href="/tabs/tab3">
          <ion-icon aria-hidden="true" :icon="atCircleOutline" />
          <ion-label>Gk tau</ion-label>
        </ion-tab-button>
      </ion-tab-bar>
    </ion-tabs>
  </ion-page>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
import { IonTabBar, IonTabButton, IonTabs, IonLabel, IonIcon, IonPage, IonRouterOutlet } from '@ionic/vue';
import { atCircleOutline, logInOutline} from 'ionicons/icons';

const route = useRoute();
</script>