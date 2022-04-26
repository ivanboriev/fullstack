<template>
    <v-card max-width="600" class="mx-auto">
        <v-card-title>Database connection</v-card-title>
        <v-card-text>
            <v-row>
                <v-col>
                    <v-select
                        :items="connections"
                        label="Connection"
                        v-model="DB_CONFIG.DB_CONNECTION"
                    ></v-select>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Database"
                        v-model="DB_CONFIG.DB_DATABASE"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Host"
                        v-model="DB_CONFIG.DB_HOST"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Port"
                        v-model="DB_CONFIG.DB_PORT"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Username"
                        v-model="DB_CONFIG.DB_USERNAME"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        label="Password"
                        v-model="DB_CONFIG.DB_PASSWORD"
                    ></v-text-field>
                </v-col>
            </v-row>

        </v-card-text>
        <v-card-actions>
            <v-btn
                text
                color="teal accent-4"
                @click="save"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import api from "../api";

export default {
    name: "DatabaseConnection",
    props: {
        config: Object
    },
    data: () => ({
        connections: ['mysql', 'pgsql'],
        DB_CONFIG: {
            DB_CONNECTION: "mysql",
            DB_DATABASE: "",
            DB_HOST: "",
            DB_PORT: "",
            DB_USERNAME: "",
            DB_PASSWORD: "",
        }
    }),

    mounted() {
        this.DB_CONFIG = {...this.config};
    },
    methods: {
        async save() {
            await api.setDatabaseConnection(this.DB_CONFIG).then(() => {
                EventBus.$emit('CONFIG:UPDATED')
            });
        }
    }
}
</script>

