<template>
    <v-data-table
        :headers="headers"
        :loading="loading"
        :items="workers"
        :options.sync="options"
        :server-items-length="meta.total"
        :footer-props="{'items-per-page-options':[10,15, 25, 50, 100]}"
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar extended>
                <v-toolbar-title>Workers</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog"
                    max-width="500px"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            New Worker
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="6"
                                    >
                                        <v-text-field
                                            v-model="editedItem.name"
                                            label="Name"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="6"
                                    >
                                        <v-text-field
                                            v-model="editedItem.salary"
                                            label="Salary"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                    >
                                        <v-autocomplete
                                            chips
                                            deletable-chips
                                            multiple
                                            small-chips
                                            v-model="editedItem.departments"
                                            :items="findItems"
                                            return-object
                                            item-text="name"
                                            item-value="id"
                                            @update:search-input="find"
                                        ></v-autocomplete>
                                    </v-col>


                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="close"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="save"
                            >
                                Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <template v-slot:extension>
                    <v-container>
                        <v-row no-gutters>
                            <v-col>
                                <v-select
                                    dense
                                    label="By department"
                                    v-model="byDepartment"
                                    :items="departments"
                                    item-text="name"
                                    item-value="id"
                                    multiple
                                >
                                    <template v-slot:selection="{ item, index }">
                                        <v-chip v-if="index === 0">
                                            <span>{{ item.name }}</span>
                                        </v-chip>
                                        <span
                                            v-if="index === 1"
                                            class="grey--text text-caption"
                                        >
                                  (+{{ byDepartment.length - 1 }} others)
                                   </span>
                                    </template>
                                </v-select>
                            </v-col>
                            <v-col>
                                <v-text-field
                                    dense
                                    v-model.number="salaryFrom"
                                    label="Salary from"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field
                                    dense
                                    v-model.number="salaryTo"
                                    label="Salary to"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-col>
                            <v-col>
                                <v-btn color="primary" @click="getWorkers">Search</v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </template>


            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                small
                @click="deleteItem(item)"
            >
                mdi-delete
            </v-icon>
        </template>
    </v-data-table>
</template>

<script>
import api from "../api";

export default {
    name: "WorkerList",
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Worker' : 'Edit Worker'
        },
        findItems() {
            return [...this.editedItem.departments, ...this.departmentsSearch]
        }
    },
    data: () => ({
        loading: false,
        dialog: false,
        dialogDelete: false,
        options: {},
        salaryFrom: null,
        salaryTo: null,
        byDepartment: null,
        headers: [
            {
                text: "#ID",
                sortable: false,
                value: "id"
            },
            {
                text: "Name",
                value: "name"
            },

            {
                text: "Salary",
                sortable: false,
                value: "salary"
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false
            },

        ],
        editedIndex: -1,
        editedItem: {
            name: '',
            salary: 0,
            departments: []

        },
        defaultItem: {
            name: '',
            salary: 0,
            departments: [],
        },
        departments: [],
        departmentsSearch: [],
        workers: [],
        meta: {
            total: null,
            current_page: null,
            per_page: null
        }
    }),
    watch: {
        options: {
            handler() {
                this.getWorkers()
            },
            deep: true,
        },
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },
    methods: {
        async getWorkers() {
            this.loading = true;
            await api.getEntitiesByFilters('workers', {
                byDepartment: this.byDepartment,
                salaryFrom: this.salaryFrom,
                salaryTo: this.salaryTo,
                ...this.options
            }).then(data => {
                this.workers = [...data.data];
                this.meta = {...data.meta};
            });
            this.loading = false;
        },

        async find(search) {
            if (search && search.length >= 3) {
                await api.findEntities('departments', search).then(data => {
                    this.departmentsSearch = data;
                });
            }
        },

        async getDepartmentList() {
            await api.getDepartmentList().then(data => {
                this.departments = [...data];
            });
        },

        editItem(item) {
            this.editedIndex = this.workers.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.workers.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        async deleteItemConfirm() {
            await api.deleteEntity('workers', this.editedItem.id).then(() => {
                this.getWorkers();
                this.closeDelete()
                EventBus.$emit('STATS:UPDATED')
            });
        },

        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        async save() {
            if (this.editedIndex > -1) {
                await api.updateEntity('workers', {...this.editedItem}).then(() => {
                    this.getWorkers();

                });
            } else {
                await api.saveEntity('workers', {...this.editedItem}).then(() => {
                    this.getWorkers();
                    EventBus.$emit('STATS:UPDATED')
                });
            }
            this.close()
        },
    },

    async mounted() {
        await this.getWorkers();
        await this.getDepartmentList();
    }
}
</script>

