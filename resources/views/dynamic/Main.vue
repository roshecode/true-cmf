<template lang="html">
    <main>
        <router-link :to="{name: 'test'}">TEST</router-link>
        <router-link to="/users/rosem">ROSEM</router-link>
        <router-link to="/users/roshe">ROSHE</router-link>
        <a href="/">Home</a>
        <a href="/user/roshe">Account</a>
        <form action="/delete" method="POST">
            <fieldset>
                <h2>Delete method</h2>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit">Delete</button>
            </fieldset>
        </form>
        <form action="/put" method="POST">
            <fieldset>
                <h2>Put method</h2>
                <input type="hidden" name="_method" value="PUT">
                <button type="submit">Put</button>
            </fieldset>
        </form>
    </main>
</template>

<script>
    export default {}
</script>
