<template>
  <div class="container">
    <div v-if="addTeam" class="row mb-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">Add New Team</div>

          <div class="card-body">
            <form>
              <div class="form-group">
                <label for="name">Name</label>
                <input v-model="team.name" type="text" :class="['form-control', {'is-invalid': validations.team.name}]">
                <div v-if="validations.team.name" class="invalid-feedback">{{ validations.team.name[0] }}</div>
              </div>
            </form>
          </div>

          <div class="card-footer">
            <button @click="storeTeam" class="btn btn-primary" type="submit">Save</button>
            <button @click="addTeam = false" class="btn btn-secondary">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div :class="[(player.players && player.players.length >= 1) || addPlayer ? 'col-md-8' : 'col-md-12']">
        <div class="card">
          <div class="card-header">Teams</div>
          <div class="card-body">
            <button @click="addTeam = true" class="btn btn-primary" type="button">Add Team</button>

            <table v-if="teams.data" class="table mt-4">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Team Name</th>
                  <th>Total Players</th>
                  <th>Created</th>
                  <th class="text-center" width="150">Actions</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="(team, index) in teams.data.data" :key="team.guid">
                  <td>{{ index + teams.data.from }}</td>
                  <td>{{ team.name }}</td>
                  <td class="text-right">{{ team.players_count }}</td>
                  <td>{{ team.created_at }}</td>
                  <td class="text-center">
                    <button  @click.prevent="showPlayers(team.guid)" class="btn btn-primary btn-sm" title="View all players">
                      <i class="fa fa-users fa-fw"></i>
                    </button>
                    <button @click.prevent="destroyTeam(team.guid)" class="btn btn-danger btn-sm" title="Delete team">
                      <i class="fa fa-trash fa-fw"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <nav v-if="teams.data" aria-label="Navigation team">
              <ul class="pagination">
                <li :class="['page-item', {'disabled': ! teams.data.prev_page_url}]"><a @click.prevent="getTeams(teams.data.prev_page_url)" class="page-link disabled" :href="teams.data.prev_page_url">Next</a></li>
                <li :class="['page-item', {'disabled': ! teams.data.next_page_url}]"><a @click.prevent="getTeams(teams.data.next_page_url)" class="page-link disabled" :href="teams.data.next_page_url">Next</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div v-if="addPlayer" class="col-md-12 mb-4">
          <div class="card">
            <div class="card-header">Add Player to Team</div>

            <div class="card-body">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input v-model="form.player.first_name" type="text" :class="['form-control', {'is-invalid': validations.player.first_name}]">
                <div v-if="validations.player.first_name" class="invalid-feedback">{{ validations.player.first_name[0] }}</div>
              </div>

              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input v-model="form.player.last_name" type="text" :class="['form-control', {'is-invalid': validations.player.last_name}]">
                <div v-if="validations.player.last_name" class="invalid-feedback">{{ validations.player.last_name[0] }}</div>
              </div>
            </div>

            <div class="card-footer">
              <button @click.prevent="storePlayer" class="btn btn-primary" type="submit">Add to Team</button>
            </div>
          </div>
        </div>

        <div v-if="player && player.players.length >= 1" class="col-md-12">
          <div class="card">
            <div class="card-header">Player in {{ player.name }}</div>
            <div class="card-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Actions</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="(p) in player.players" :key="p.guid">
                    <td>{{ p.first_name }}</td>
                    <td>{{ p.last_name }}</td>
                    <td>
                      <button @click.prevent="destroyPlayer(p.guid)" class="btn btn-danger btn-sm" title="Delete player from team">
                        <i class="fa fa-fw fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="card-footer">
              <button @click.prevent="player.players = []; addPlayer = false" class="btn btn-secondary" type="button">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TeamIndex',

  data () {
    return {
      validations: {
        team: [],
        player: []
      },
      addTeam: false,
      addPlayer: false,
      teams: {},
      player: {
        players: []
      },
      team: {
        name: ''
      },
      form: {
        player: {
          team_id: '',
          first_name: '',
          last_name: '',
        }
      }
    }
  },

  created () {
    this.getTeams()
  },

  methods: {
    storePlayer () {
      this.validations.player = []

      this.form.player.team_id = this.player.id

      axios.post('/api/player', this.form.player)
        .then(response => {
          this.showPlayers(this.player.guid)
          this.getTeams()

          Object.keys(this.form.player).forEach(key => {
            this.form.player[key] = ''
          })
        })
        .catch(e => {
          if (e.response.status == 422) {
            this.validations.player = e.response.data.errors
          }
        })
    },

    destroyPlayer (guid) {
      axios.delete(`/api/player/${guid}`)
        .then(response => {
          this.showPlayers(this.player.guid)
        })
        .catch(e => console.error(e))
    },

    showPlayers (guid) {
      this.addPlayer = true
      axios.get(`/api/team/${guid}/players`)
        .then(response => {
          this.player = response.data
        })
    },

    async getTeams (url = null) {
      this.teams = await axios.get(url || '/api/team')
    },

    destroyTeam (guid) {
      axios.delete(`/api/team/${guid}`)
        .then(response => {
          this.getTeams()
        })
        .catch(e => console.error(e))
    },

    storeTeam () {
      this.validations.team = []

      axios.post(`/api/team`, this.team)
        .then(response => {
          this.team.name = ''

          this.getTeams()
        })
        .catch(e => {
          if (e.response.status == 422) {
            this.validations.team = e.response.data.errors
          }
        })
    }
  }
}
</script>
