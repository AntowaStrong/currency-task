<template>
  <div class="currency">
    <span>From: </span>
    <select id="from" v-model="from" @input="getCurrency"> 
      <option v-for="currency in fromOption" v-bind:key="currency" v-bind:value="currency">
        {{ currency }}
      </option>
    </select>
    <span>To: </span>
    <select id="to" v-model="to" @input="getCurrency">
      <option v-for="currency in toOptions" v-bind:key="currency" v-bind:value="currency">
        {{ currency }}
      </option>
    </select>
    <div class="amount">
      <span>Amount: </span>
      <input id="amount" type="number" v-model="amount" @input="getCurrency">
      <div v-if="isLoading" class="loader"></div>
    </div>

    <div class="results" v-if="showResults">
      <span>Result: </span>
      <span>{{ result }}</span>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Currency',
  data() {
    return {
      debounce: null,
      loading: false,
      from: null,
      to: null,
      amount: null,
      result: null,
      currencies: [
        'USD', 
        'EUR',
        'CZK'
      ]
    }
  },
  computed: {
    showResults () {
      return this.result !== null && this.result !== '' && !this.loading
    },
    isLoading () {
      return this.loading
    },
    fromOption () {
      let values = [];

      for (let currency of this.currencies) {
        if (this.to !== currency) {
          values.push(currency)
        }
      }

      return values
    },
    toOptions () {
      let values = [];

      for (let currency of this.currencies) {
        if (this.from !== currency) {
          values.push(currency)
        }
      }

      return values
    }
  },
  created() {
    this.from = this.currencies[0]
    this.to = this.currencies[1]
  },
  methods: {
    getCurrency () {
      clearTimeout(this.debounce)

      this.debounce = setTimeout(async () => {
        this.loading = true
        this.result = null

        try {
          let response = await axios.get('http://localhost:9050/api/quote', { 
            params:{ 
              amount: this.amount,
              fromCurrencyCode: this.from,
              toCurrencyCode: this.to
            }
          })
    
          this.result = response.data.amount
          this.loading = false 

        } catch (e) {
          this.loading = false
        }
      }, 400)
    }
  }
}
</script>

<style scoped>
  .currency {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 30px 40px;
    border: 1px solid #000;
    border-radius: 10px;
  }

  span {
    text-align: left;
    width: 100%;
  }

  .results {
    padding: 20px 0 0 0;
  }

  .amount {
    padding: 10px 0 0 0;
    position: relative;
    display: flex;
    flex-direction: column;
  }

  .loader,
  .loader:after {
    border-radius: 50%;
    width: 20px;
    height: 20px;
  }

  .loader {
    font-size: 10px;
    position: absolute;
    bottom: 2px;
    right: -30px;
    border-top: 2px solid rgba(0, 0, 0, 0.2);
    border-right: 2px solid rgba(0, 0, 0, 0.2);
    border-bottom: 2px solid rgba(0, 0, 0, 0.2);
    border-left: 2px solid #000;
    -webkit-transform: translateZ(0);
    -ms-transform: translateZ(0);
    transform: translateZ(0);
    -webkit-animation: load8 1.1s infinite linear;
    animation: load8 1.1s infinite linear;
  }

  @-webkit-keyframes load8 {
    0% {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }

  @keyframes load8 {
    0% {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
</style>
