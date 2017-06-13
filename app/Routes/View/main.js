import Main from '../../Views/Main.vue';

export default [
    {
        path: '/',
        name: 'home',
        component: Main
    },
    {
        path: '/test',
        name: 'test',
        component: 'Test'
    },
    {
        path: '/users/:slug',
        name: 'user',
        component: 'Users/User',
        props: true
    }
];
