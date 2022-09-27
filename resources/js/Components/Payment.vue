<template>
  <div class="payment">
    <div ref="dropin" />
    <slot
      name="button"
      :submit="submit"
    >
      <button
        :class="btnClass"
        @click="submit"
      >
        {{ btnText }}
      </button>
    </slot>
  </div>
</template>

<script>
import dropIn from "braintree-web-drop-in";
export default {
  props: {
    authorization: {
      required: true,
      type: String
    },
    locale: {
      type: String,
      default: "en_US"
    },
    btnText: {
      type: String,
      default: "Subscribe"
    },
    btnClass: {
      type: String,
      default: "btn btn-primary"
    },
    paypal: {
      type: Object,
      default: undefined
    }
  },
  data() {
    return {
      instance: null, // The DropIn Instance
    };
  },
  mounted() {
    let config = {
      authorization: this.authorization,
      container: this.$refs.dropin,
      locale: this.locale,
      paypal: this.paypal,
    };

    dropIn.create(config, (createErr, instance) => {
      if (createErr) {
        this.$emit("loadFail", createErr);
        return;
      }
      this.instance = instance;
      console.log(this.instance)
      // Load event
      this.$emit("load", this.instance);
    });
  },
  beforeDestroy () {
    if (this.instance) {
      this.instance.teardown((err) => {
        if (err) { 
          console.error("An error occurred during teardown:", err); 
        }
      });
    }
  },
  methods: {
    onLoad (instance) {
      this.instance = instance;
    },
    submit (event) {
      if (event) {
        event.preventDefault();
      }
      let requestPaymentConfig = {};
      if (this.threeDSecure === true) {
        requestPaymentConfig.threeDSecure = this.threeDSecureParameters;
      }
      this.instance.requestPaymentMethod(requestPaymentConfig, (err, payload) => {
        if (err) {
          // No payment method is available.
          // An appropriate error will be shown in the UI.
          this.$emit("error", err);
          return;
        }
        this.$emit("success", payload);
      });
    }
  }
};
</script>