export default [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/HomeView.vue'),
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'page_not_found',
        component: () => import('../views/PageNotFoundView.vue'),
    },
];
