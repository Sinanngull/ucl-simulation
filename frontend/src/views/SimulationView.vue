<template>
  <section class="simulation-section d-flex align-items-center justify-content-center min-vh-100">
    <div class="container-lg py-5">
      <h2 class="text-center mb-5 fw-bold text-primary display-6">
        🏆 Simulation
      </h2>

      <div class="row g-5 justify-content-center">
        <div class="col-12 col-lg-6">
          <h5 class="text-center">League Table</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-sm text-center shadow-sm">
              <thead class="table-dark">
              <tr>
                <th>Team</th>
                <th>Pts</th>
                <th>W</th>
                <th>D</th>
                <th>L</th>
                <th>GD</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="team in table" :key="team.team">
                <td class="d-flex align-items-center gap-2">
                  <img :src="getLogoPath(team.team)" :alt="team.team + ' logo'" width="20" height="20" />
                  {{ team.team }}
                </td>
                <td>{{ team.points }}</td>
                <td>{{ team.wins }}</td>
                <td>{{ team.draws }}</td>
                <td>{{ team.losses }}</td>
                <td>{{ team.goal_difference }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <h5 class="text-center">Week {{ nextWeekNumber ?? '-' }}</h5>
          <ul class="list-group text-center shadow-sm">
            <li
              v-for="match in nextWeekMatches"
              :key="match.id"
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              <div class="d-flex align-items-center gap-2">
                <img :src="getLogoPath(match.home_team.name)" :alt="match.home_team.name + ' logo'" width="20" height="20" />
                <span>{{ match.home_team.name }}</span>
              </div>
              <span>-</span>
              <div class="d-flex align-items-center gap-2">
                <span>{{ match.away_team.name }}</span>
                <img :src="getLogoPath(match.away_team.name)" :alt="match.away_team.name + ' logo'" width="20" height="20" />
              </div>
            </li>
          </ul>
          <div v-if="!nextWeekMatches.length" class="text-muted text-center mt-3">
            All matches have been played.
          </div>
        </div>
      </div>

      <div class="d-flex flex-wrap justify-content-center gap-3 mt-5">
        <button class="btn btn-info px-4 py-2 fw-semibold" @click="simulateAll" :disabled="loading">
          {{ loading ? 'Simulating All Matches...' : 'Simulate All Matches' }}
        </button>

        <button class="btn btn-primary px-4 py-2 fw-semibold" @click="simulateWeek" :disabled="loading || finished">
          {{ loading ? 'Simulating...' : 'Simulate Week' }}
        </button>

        <button class="btn btn-danger px-4 py-2 fw-semibold" @click="resetData" :disabled="loading">
          Reset
        </button>
      </div>

      <div v-if="champion" class="mt-5 text-center">
        <h4 class="text-success fw-bold">🏆 Champion: {{ champion }}</h4>
      </div>

      <div v-if="predictions.length" class="mt-5">
        <h5 class="text-center mb-3">🔮 Championship Predictions</h5>
        <ul class="list-group">
          <li
            v-for="prediction in predictions"
            :key="prediction.team"
            class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
              <img :src="getLogoPath(prediction.team)" :alt="prediction.team + ' logo'" width="20" height="20" />
              <span>{{ prediction.team }}</span>
            </div>
            <span>{{ prediction.chance }}%</span>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import confetti from 'canvas-confetti'

const table = ref<any[]>([])
const nextWeekMatches = ref<any[]>([])
const nextWeekNumber = ref<number | null>(null)
const loading = ref(false)
const finished = ref(false)
const champion = ref<string | null>(null)
const predictions = ref<any[]>([])

const getLogoPath = (teamName: string) => {
  const fileName = teamName.toLowerCase().replace(/\s/g, '-') + '.png'
  return `/logos/${fileName}`
}

const fetchAllData = async () => {
  try {
    const [tableRes, fixtureRes, predictionsRes] = await Promise.all([
      axios.get('/api/league-table'),
      axios.get('/api/next-week-matches'),
      axios.get('/api/championship-predictions')
    ])
    table.value = tableRes.data
    nextWeekMatches.value = fixtureRes.data.matches
    nextWeekNumber.value = fixtureRes.data.week
    predictions.value = predictionsRes.data

    if (!fixtureRes.data.matches.length && table.value.length) {
      const sorted = [...table.value].sort((a, b) => b.points - a.points || b.goal_difference - a.goal_difference)
      champion.value = sorted[0].team
      launchFireworks()
    }
  } catch (err) {
    console.error('Failed to fetch data:', err)
  }
}

const simulateWeek = async () => {
  try {
    loading.value = true
    const res = await axios.get('/api/simulate-week')
    if (res.data.message === 'All weeks have been simulated.') {
      finished.value = true
    }
    await fetchAllData()
  } catch (err) {
    console.error('Simulation error:', err)
  } finally {
    loading.value = false
  }
}

const simulateAll = async () => {
  try {
    loading.value = true
    await axios.get('/api/simulate-matches')
    finished.value = true
    await fetchAllData()
  } catch (err) {
    console.error('Full simulation error:', err)
  } finally {
    loading.value = false
  }
}

const resetData = async () => {
  try {
    loading.value = true
    await axios.post('/api/reset')
    finished.value = false
    champion.value = null
    predictions.value = []
    await fetchAllData()
  } catch (err) {
    console.error('Reset error:', err)
  } finally {
    loading.value = false
  }
}

const launchFireworks = () => {
  const duration = 3000
  const animationEnd = Date.now() + duration
  const interval = setInterval(() => {
    const timeLeft = animationEnd - Date.now()
    if (timeLeft <= 0) clearInterval(interval)
    confetti({
      particleCount: 80,
      spread: 70,
      origin: { y: 0.6 }
    })
  }, 300)
}

onMounted(fetchAllData)
</script>

<style scoped>
.simulation-section {
  background: linear-gradient(to right, #f8f9fa, #dee2e6);
  border-radius: 1.25rem;
}

.table {
  font-size: 0.95rem;
}
</style>
