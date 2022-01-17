<template>
  <transition
    name="fade"
    mode="out-in"
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
.fade{
  &-enter-active,
  &-leave-active {
    transition: opacity .5s;
  }

  &-enter,
  &-leave-to {
    opacity: 0;
  }
}
</style>
