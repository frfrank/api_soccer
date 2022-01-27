<template>
  <div class="inputType">
    <p>{{ title }}</p>
    <input class="formInput" type="type" />
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
};
</script>

<style lang="scss" scoped>
.inputType {  
  input{
    border: none;
    border-bottom: solid 1px;
    width: 100%;
}

}


</style>