<template>
  <div id="app">
    <Currency></Currency>
  </div>
</template>

<script>
import Currency from './components/Currency.vue'

export default {
  name: 'App',
  components: {
    Currency
  }
}
</script>

<style>
* {
  box-sizing: border-box;
}

html,
body {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  height: 100%;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
