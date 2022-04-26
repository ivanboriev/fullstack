<template>
    <v-app>
        <v-app-bar app
                   elevation="4">
            <v-toolbar-title>{{ config.name }}</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn-toggle
                borderless
            >
                <v-btn to="departments"
                       :disabled="needInstall"
                >
                    <v-icon left>
                        mdi-office-building
                    </v-icon>

                    <span class="hidden-sm-and-down">Departments</span>
                    <v-chip small color="info">{{ stats.departments }}</v-chip>


                </v-btn>
                <v-btn to="workers"
                       :disabled="needInstall"
                >
                    <v-icon left>
                        mdi-account-hard-hat
                    </v-icon>

                    <span class="hidden-sm-and-down">Workers</span>
                    <v-chip small color="info">{{ stats.workers }}</v-chip>


                </v-btn>
            </v-btn-toggle>
        </v-app-bar>

        <v-main>
            <template v-if="needInstall">
                <v-container>
                    <DatabaseConnection v-if="!config.db_connection && config.db_config" :config="config.db_config"/>
                    <SampleData v-if="!config.sample_data && config.db_connection"/>
                </v-container>
            </template>

            <template v-else>
                <v-container>
                    <router-view></router-view>
                </v-container>
            </template>

        </v-main>
    </v-app>
</template>

<script>
import DepartmentList from "./DepartmentList";
import WorkerList from "./WorkerList";
import DatabaseConnection from "./DatabaseConnection";
import SampleData from "./SampleData"
import api from "../api";

export default {
    name: "Dashboard",
    computed: {
        needInstall() {
            return !this.config.db_connection || !this.config.sample_data;
        }
    },
    data: () => ({
        stats: {
            departments: 0,
            workers: 0,
        },
        config: {
            name: '',
            db_connection: false,
            db_config: null,
            sample_data: false
        }
    }),
    components: {
        DatabaseConnection,
        SampleData,
        DepartmentList,
        WorkerList
    },
    methods: {
        async fetchConfig() {
            this.config = await api.fetchApplicationConfig();

            if (this.needInstall === false) {
                this.$router.push('/departments');
            }
        },

        async getStats() {
            await api.stats().then(stats => {
                this.stats = {...stats}
            });
        }
    },

    async mounted() {
        await this.fetchConfig().then(() => {
            if (this.needInstall === false) {
                this.getStats();
            }
        });

        EventBus.$on('CONFIG:UPDATED', this.fetchConfig);
        EventBus.$on('STATS:UPDATED', this.getStats);

    }

}
</script>
