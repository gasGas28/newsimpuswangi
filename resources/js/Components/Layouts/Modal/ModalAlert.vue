<template>
  <div
    v-if="show"
    class="modal fade show d-block"
    tabindex="-1"
    style="background: rgba(15, 23, 42, 0.48)"
    @click.self="close"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">

        <div class="modal-header border-0">
          <h5 class="modal-title" :class="titleClass">
            {{ title }}
          </h5>

          <button
            type="button"
            class="btn-close"
            @click="close"
          ></button>
        </div>

        <div class="modal-body">
          <p v-if="message">{{ message }}</p>

          <ul v-if="items?.length" class="mb-0">
            <li
              v-for="(item, index) in items"
              :key="index"
            >
              {{ item }}
            </li>
          </ul>
        </div>

        <div class="modal-footer border-0">
          <button
            type="button"
            class="btn"
            :class="buttonClass"
            @click="close"
          >
            {{ buttonText }}
          </button>

          <button
            v-if="showSecondaryButton"
            type="button"
            class="btn btn-primary"
            @click="$emit('secondary-action')"
          >
            {{ secondaryButtonText }}
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  show: Boolean,

  type: {
    type: String,
    default: 'success', // success, warning, error
  },

  title: String,
  message: String,

  items: {
    type: Array,
    default: () => [],
  },

  buttonText: {
    type: String,
    default: 'OK',
  },

  showSecondaryButton: {
    type: Boolean,
    default: false,
  },

  secondaryButtonText: {
    type: String,
    default: '',
  },
});

const emit = defineEmits([
  'close',
  'secondary-action',
]);

const close = () => emit('close');

const titleClass = computed(() => ({
  'text-success': props.type === 'success',
  'text-warning': props.type === 'warning',
  'text-danger': props.type === 'error',
}));

const buttonClass = computed(() => {
  if (props.type === 'warning') {
    return 'btn-warning text-white';
  }

  if (props.type === 'error') {
    return 'btn-danger';
  }

  return 'btn-success';
});
</script>