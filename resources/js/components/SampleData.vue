<template>
    <v-card width="600" class="mx-auto" :loading="loading">
        <v-card-title>Install Sample Data</v-card-title>

        <v-card-text class="text-center" v-if="loading">
            <h3>{{ bathName }}</h3>
            <h5 v-show="batch.processedJobs > 0">{{ batch.processedJobs }} / {{ batch.totalJobs }}</h5>
        </v-card-text>

        <v-card-actions>
            <v-btn
                text
                color="teal accent-4"
                @click="install"
            >
                Install
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import api from "../api";

export default {
    name: "SampleData",
    computed: {
        bathName() {
            if (this.batch.processedJobs === 1) {
                return "Creating departments ..."
            }
            if (this.batch.processedJobs === 2) {
                return "Creating workers ..."
            }
            if (this.batch.processedJobs === 3) {
                return "Add workers in departments ..."
            }
            if(this.loading){
                return "Preparing for installation ..."
            }
            return '';

        }
    },
    data: () => ({
        loading: false,
        batchId: null,
        batch: {
            processedJobs: null,
            totalJobs: null,
            finishedAt: null
        },
    }),
    methods: {
        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },

        async install() {
            api.startQueue();
            await api.install().then((batchId => {
                this.batchId = batchId;
            }));
            this.loading = true;
            await this.process();


        },
        async process() {
            await api.batch(this.batchId).then((batch => {
                this.batch = {...batch};
            }));

            await this.sleep(1500).then(() => {
                if (this.batch.finishedAt == null) {
                    this.process();
                } else {
                    this.loading = false;
                    EventBus.$emit('CONFIG:UPDATED');
                    EventBus.$emit('STATS:UPDATED');
                }
            });


        }
    }
}
</script>

<style scoped>

</style>
