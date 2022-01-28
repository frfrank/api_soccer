<template>
  <div class="inputType">
    <p>{{ title }} </p>
    <input class="formInput" v-bind="inputAttributes" />
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: "",
    },
  },

  computed: {
    type() {
      if (this.$attrs?.extra === "") {
        return "text";
      }
      return this.$attrs?.extra;
    },

     inputAttributes() {
      return Object.assign({}, this.$attrs);
    },
  },
};
</script>

<style lang="scss" scoped>
.inputType {
  input {
    border: none;
    border-bottom: solid 1px;
    width: 100%;
  }
}
</style>