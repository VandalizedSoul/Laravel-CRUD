import CreateTask from './components/CreateTask.vue';
// import EditProduct from './components/EditTask.vue';
import Task from './components/Task.vue' ;
export const routes = [
    {
        name: 'home',
        path: '/',
        component: Task
    },  
    {
        name: 'create',
        path: '/create',
        component: CreateTask
    },
    // {
    //     name: 'edit',
    //     path: '/edit/:id',
    //     component: EditProduct
    // }
];