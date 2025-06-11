<template>
  <section class="fixture-section d-flex align-items-center justify-content-center min-vh-100">
    <div class="container-lg py-5">
      <div class="row justify-content-center">
        <div class="col-12">
          <h2 class="text-center mb-5 fw-bold text-primary display-6">
            📅 Match Fixtures
          </h2>

          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div
              v-for="week in groupedFixtures"
              :key="week.week"
              class="col"
            >
              <div class="card h-100 shadow border-0">
                <div class="card-header bg-dark text-white fw-bold text-center">
                  Week {{ week.week }}
                </div>
                <ul class="list-group list-group-flush">
                  <li
                    v-for="(match, index) in week.matches"
                    :key="index"
                    class="list-group-item d-flex justify-content-between align-items-center"
                  >
                    <div class="d-flex align-items-center gap-2">
                      <img
                        :src="getLogoPath(match.home_team)"
                        :alt="match.home_team + ' logo'"
                        width="24"
                        height="24"
                        style="object-fit: contain"
                      />
                      <span>{{ match.home_team }}</span>
                    </div>

                    <span>-</span>

                    <div class="d-flex align-items-center gap-2">
                      <span>{{ match.away_team }}</span>
                      <img
                        :src="getLogoPath(match.away_team)"
                        :alt="match.away_team + ' logo'"
                        width="24"
                        height="24"
                        style="object-fit: contain"
                      />
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="text-center mt-5">
            <button
              class="btn btn-success btn-lg px-5 py-2 fw-semibold shadow"
              @click="goToSimulation"
            >
              🎮 Start Simulation
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const groupedFixtures = ref<any[]>([])
const router = useRouter()

const fetchFixtures = async () => {
  try {
    const res = await axios.get('/api/week-by-week')
    groupedFixtures.value = res.data
  } catch (err) {
    console.error('Failed to fetch fixtures:', err)
  }
}

const goToSimulation = () => {
  router.push('/simulation')
}


const getLogoPath = (teamName: string) => {
  const fileName = teamName.toLowerCase().replace(/\s/g, '-') + '.png'
  return `/logos/${fileName}`
}

onMounted(fetchFixtures)
</script>

<style scoped>
.fixture-section {
  background: linear-gradient(to right, #f8f9fa, #dee2e6);
  border-radius: 1.25rem;
}

.card-header {
  font-size: 1.1rem;
}

.list-group-item {
  font-size: 0.95rem;
}
</style>
