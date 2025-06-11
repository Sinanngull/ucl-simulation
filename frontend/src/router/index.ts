import { createRouter, createWebHistory } from 'vue-router'

import TeamListView from '../views/TeamListView.vue'
import FixturesView from '../views/FixturesView.vue'
import SimulationView from '../views/SimulationView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'teams',
      component: TeamListView,
    },
    {
      path: '/fixtures',
      name: 'fixtures',
      component: FixturesView,
    },
    {
      path: '/simulation',
      name: 'simulation',
      component: SimulationView,
    }
  ]
})

export default router
