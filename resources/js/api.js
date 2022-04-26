const axios = require('axios');
import Config from "./config";

class Api {

    constructor(client) {
        this._client = client;
    }

    async fetchApplicationConfig() {
        let response = await this._client.get('/api/application_config');
        return new Config(response.data);
    }

    async setDatabaseConnection(config) {
        await this._client.post('/api/set_database_connection', {...config});
    }

    startQueue() {
        this._client.post('/api/queue_start');

    }

    async install(config) {
        let response = await this._client.post('/api/install_sample_data');
        return response.data;
    }

    async batch(batchId) {
        let response = await this._client.get('/api/batch/' + batchId);
        return response.data;
    }

    async stats() {
        let response = await this._client.get('/api/stats');
        return response.data;
    }

    async getDepartmentList() {
        let response = await this._client.get('/api/department_list');
        return response.data;
    }

    async findEntities(entity, search) {
        let response = await this._client.get('/api/' + entity + '/find?search=' + search);
        return response.data;
    }


    async updateEntity(entity, payload) {
        await this._client.put('/api/' + entity + '/' + payload.id, {...payload})
    }

    async saveEntity(entity, payload) {
        await this._client.post('/api/' + entity, {...payload})
    }

    async deleteEntity(entity, id) {
        await this._client.delete('/api/' + entity + '/' + id)
    }


    async getEntitiesByFilters(entity, options) {
        let url = this.parseOptionsToUrl(options);

        let response = await this._client.get('/api/' + entity + url);
        return response.data;
    }

    parseOptionsToUrl(options) {
        let {itemsPerPage: paginate, page, salaryFrom, salaryTo, byDepartment} = options;

        let sortByName = options.sortBy[0] === "name" ? '&name=' : '';

        if (sortByName !== '') {
            sortByName += options.sortDesc[0] === true ? 'desc' : 'asc';
        }

        let salary = salaryFrom ? "&salaryFrom=" + salaryFrom : '';
        salary += salaryTo ? "&salaryTo=" + salaryTo : '';

        byDepartment = byDepartment ? '&byDepartment=' + byDepartment : '';

        return '?page=' + page + salary + sortByName + byDepartment + '&paginate=' + paginate;
    }
}

export default new Api(axios);
