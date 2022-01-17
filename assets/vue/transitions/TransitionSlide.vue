<template>
  <transition
    :name="name"
    mode="out-in"
    :enter-active-class="`${name}-enter-active ${name}`"
    :leave-active-class="`${name}-leave-active ${name}`"
    appear
  >
    <slot />
  </transition>
</template>

<script>
const sides = ['left', 'right', 'top', 'bottom'];
export default {
  props: {
    side: {
      type: String,
      default: sides[0],
      validator: (side) => sides.includes(side),
    },
  },

  computed: {
    name() {
      return `slide-${this.side}`;
    },
  },
};
</script>

<style lang="scss" scoped>
.slide-left {
  --local-translate-x: 4rem;
  --local-translate-y: 0rem;
}

.slide-right {
  --local-translate-x: -4rem;
  --local-translate-y: 0rem;
}

.slide-top {
  --local-translate-x: 0rem;
  --local-translate-y: 4rem;
}

.slide-bottom {
  --local-translate-x: 0rem;
  --local-translate-y: -4rem;
}

.slide-left,
.slide-right,
.slide-top,
.slide-bottom{
  &-enter-active,
  &-leave-active {
    transition: all 0.5s;
    overflow: hidden;
  }

  &-enter {
    opacity: 0;
    transform: translate(calc(var(--local-translate-x) * -1), calc(var(--local-translate-y) * -1));
  }

  &-leave-to {
    opacity: 0;
    transform: translate(var(--local-translate-x), var(--local-translate-y));
  }
}
</style>
