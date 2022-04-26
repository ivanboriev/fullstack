<template>
    <v-data-table
        :headers="headers"
        :items="departments"
        :loading="loading"
        :options.sync="options"
        :server-items-length="meta.total"
        :footer-props="{'items-per-page-options':[10,15, 25, 50, 100]}"
        show-expand
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar extended>
                <v-toolbar-title>Departments</v-toolbar-title>
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
                            New Department
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
                                        <v-menu
                                            v-model="startedAtPicker"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="auto"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                    v-model="editedItem.started_at"
                                                    label="Started at"
                                                    prepend-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="editedItem.started_at"
                                                @input="startedAtPicker = false"
                                            ></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col
                                        cols="12"
                                    >
                                        <v-autocomplete
                                            chips
                                            deletable-chips
                                            multiple
                                            small-chips
                                            v-model="editedItem.workers"
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
                                <v-text-field
                                    v-model.number="salaryFrom"
                                    label="Salary from"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-col>
                            <v-col>
                                <v-text-field
                                    v-model.number="salaryTo"
                                    label="Salary to"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </v-col>
                            <v-col>
                                <v-btn color="primary" @click="getDepartments">Search</v-btn>
                            </v-col>
                        </v-row>
                    </v-container>
                </template>


            </v-toolbar>
        </template>

        <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length">
                <v-data-table
                    disable-pagination
                    :headers="expandHeaders"
                    :items="item.workers"
                    hide-default-footer
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title>Workers</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                    </template>
                </v-data-table>
            </td>
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
    name: "DepartmentList",
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Department' : 'Edit Department'
        },
        findItems() {
            return [...this.editedItem.workers, ...this.workers]
        }
    },
    data: () => ({
        loading: false,
        dialog: false,
        dialogDelete: false,
        startedAtPicker: false,
        options: {},
        salaryFrom: null,
        salaryTo: null,
        expandHeaders: [
            {
                text: "#ID",
                value: "id"
            },
            {
                text: "Name",
                value: "name"
            },


            {
                text: "Salary",
                value: "salary"
            },
        ],
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
                text: 'Workers',
                sortable: false,
                value: 'workers.length'
            },

            {
                text: "Started at",
                sortable: false,
                value: "started_at"
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false
            },
            {
                text: '',
                sortable: false,
                value: 'data-table-expand'
            },

        ],
        departments: [],
        workers: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            started_at: '',
            workers: []

        },
        defaultItem: {
            name: '',
            started_at: '',
            workers: [],
        },
        meta: {
            total: null,
            current_page: null,
            per_page: null
        }
    }),
    watch: {
        options: {
            handler() {
                this.getDepartments()
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
        async getDepartments() {
            this.loading = true;
            await api.getEntitiesByFilters('departments', {
                salaryFrom: this.salaryFrom,
                salaryTo: this.salaryTo, ...this.options
            }).then(data => {
                this.departments = [...data.data];
                this.meta = {...data.meta};
            });
            this.loading = false;
        },

        async find(search) {
            if (search && search.length >= 3) {
                await api.findEntities('workers', search).then(data => {
                    this.workers = data;
                });
            }
        },

        editItem(item) {
            this.editedIndex = this.departments.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.departments.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        async deleteItemConfirm() {
            await api.deleteEntity('departments', this.editedItem.id).then(() => {
                this.getDepartments();
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
                await api.updateEntity('departments', {...this.editedItem}).then(() => {
                    this.getDepartments();

                });
            } else {
                await api.saveEntity('departments', {...this.editedItem}).then(() => {
                    this.getDepartments();
                    EventBus.$emit('STATS:UPDATED')
                });
            }
            this.close()
        },
    },
    async mounted() {
        await this.getDepartments();
    }
}
</script>

