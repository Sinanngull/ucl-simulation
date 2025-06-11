<template>
  <section class="team-list-section d-flex align-items-center justify-content-center min-vh-100">
    <div class="container-xl">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
          <div class="card p-5 shadow-lg border-0 rounded-4 bg-white">
            <h2 class="text-center mb-4 fw-bold text-primary display-6">
              🏆 Participating Teams
            </h2>

            <table class="table table-hover text-center align-middle">
              <thead class="table-dark">
              <tr>
                <th scope="col">Team</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="team in teams" :key="team.id">
                <td class="team-cell text-center">
                  <img
                    :src="getLogoPath(team.name)"
                    :alt="team.name + ' logo'"
                    class="team-logo"
                  />
                  <span class="fw-medium mt-2">{{ team.name }}</span>
                </td>
              </tr>
              </tbody>
            </table>

            <div class="text-center mt-4">
              <button class="btn btn-info w-100 py-3 fw-semibold fs-5 shadow-sm" @click="generateFixtures">
                📅 Generate Fixtures
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'

const teams = ref<any[]>([])

const fetchTeams = async () => {
  try {
    const response = await axios.get('/api/teams')
    teams.value = response.data
  } catch (err) {
    console.error('Failed to fetch teams:', err)
  }
}

const generateFixtures = async () => {
  try {
    await axios.post('/api/generate-fixtures')
    window.location.href = '/fixtures'
  } catch (err) {
    console.error('Failed to generate fixtures:', err)
  }
}

const getLogoPath = (teamName: string) => {
  const fileName = teamName.toLowerCase().replace(/\s/g, '-') + '.png'
  return `/logos/${fileName}`
}

onMounted(fetchTeams)
</script>

<style scoped>


.card {
  border-radius: 1.25rem;
  padding: 2rem !important;
  max-width: 100%;
}

.team-cell {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 0.4rem 0;
  font-size: 0.85rem;
}

.team-logo {
  width: 32px;
  height: 32px;
  object-fit: contain;
  margin-bottom: 0.3rem;
}

.table {
  font-size: 0.85rem;
}

h2.display-6 {
  font-size: 1.75rem;
}


</style>
