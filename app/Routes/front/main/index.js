export default [
    {
        path: '*',
        name: 404,
        component: '404'
    },
    {
        path: '/',
        name: 'home',
        component: 'Main'
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
