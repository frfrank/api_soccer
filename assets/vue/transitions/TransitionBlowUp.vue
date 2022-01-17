<template>
  <transition
    name="blow"
    mode="out-in"
    enter-active-class="blow-enter-active blow"
    leave-active-class="blow-leave-active blow"
    appear
  >
    <slot />
  </transition>
</template>

<script>
export default {
};
</script>

<style lang="scss" scoped>

//todo Add effect @See: https://codepen.io/designcouch/pen/obvKxm
.blow {
  &-enter-active,
  &-leave-active {
    transition: all 0.25s;
    overflow: hidden;
  }

  &-enter {
    opacity: 0;
  }

  &-leave-to {
    opacity: 0;
  }
}
</style>
