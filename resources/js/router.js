import Vue from 'vue'
import VueRouter from 'vue-router'
import DepartmentList from "./components/DepartmentList";
import WorkerList from "./components/WorkerList";

Vue.use(VueRouter);

const routes = [
    {path: '/departments', component: DepartmentList},
    {path: '/workers', component: WorkerList},
]

export default new VueRouter({
    history: true,
    routes,
})
