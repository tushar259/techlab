import {createRouter, createWebHistory} from 'vue-router';

import NotFound from './components/NotFound.vue';
import Home from './components/Home.vue';
import UpdateImage from './components/UpdateImage.vue';

const routes = [
    { path: '/:pathMatch(.*)*', component: NotFound},
    { path: '/', component: Home},
    { path: '/update-image/:imageId', component: UpdateImage},
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;