class Config {
    #name = "";
    #db_connection = false;
    #db_config = [];
    #sample_data = false;

    constructor(data) {
        let {name, db_connection, sample_data, db_config} = data;
        this.#name = name;
        this.#db_connection = db_connection;
        this.#sample_data = sample_data;
        this.#db_config = db_config;
    }

    get name() {
        return this.#name;
    }

    set name(value) {
        this.#name = value;
    }

    get db_connection() {
        return this.#db_connection;
    }

    set db_connection(value) {
        this.#db_connection = value;
    }

    get sample_data() {
        return this.#sample_data;
    }

    set sample_data(value) {
        this.#sample_data = value;
    }

    get db_config() {
        return this.#db_config;
    }

    set db_config(value) {
        this.#db_config = [...value];
    }
}

export default Config;
