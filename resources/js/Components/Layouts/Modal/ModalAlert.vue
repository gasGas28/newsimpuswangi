<template>
  <!-- Modal warning / error: tetap pakai dialog + tombol, karena user perlu membaca & menutup manual -->
  <div
    v-if="show && !isSuccess"
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

          <button type="button" class="btn-close" @click="close"></button>
        </div>

        <div class="modal-body">
          <p v-if="message">{{ message }}</p>

          <ul v-if="items?.length" class="mb-0">
            <li v-for="(item, index) in items" :key="index">
              {{ item }}
            </li>
          </ul>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn" :class="buttonClass" @click="close">
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

  <!-- Modal success: cukup centang + animasi, auto-hilang, tanpa tombol -->
  <transition name="fade">
    <div v-if="show && isSuccess" class="success-toast-overlay">
      <div class="success-toast">
        <svg class="success-checkmark" viewBox="0 0 52 52">
          <circle class="success-checkmark-circle" cx="26" cy="26" r="24" fill="none" />
          <path class="success-checkmark-check" fill="none" d="M14 27l7 7 16-16" />
        </svg>

        <p v-if="title" class="success-toast-title">{{ title }}</p>
        <p v-if="message" class="success-toast-message">{{ message }}</p>
      </div>
    </div>
  </transition>
</template>

<script setup>
  import { computed, watch } from 'vue';

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

    // Khusus type="success": durasi tampil sebelum auto-hilang (ms), < 2 detik
    duration: {
      type: Number,
      default: 1500,
    },
  });

  const emit = defineEmits(['close', 'secondary-action']);

  const isSuccess = computed(() => props.type === 'success');

  const close = () => emit('close');

  // Auto-close khusus modal success, tidak butuh interaksi user
  watch(
    () => props.show,
    (val) => {
      if (val && isSuccess.value) {
        setTimeout(() => emit('close'), props.duration);
      }
    }
  );

  const titleClass = computed(() => ({
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

<style scoped>
  .success-toast-overlay {
    position: fixed;
    inset: 0;
    z-index: 1080;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15, 23, 42, 0.28);
  }

  .success-toast {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 28px 32px;
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.18);
    animation: popIn 0.25s ease;
  }

  .success-checkmark {
    width: 64px;
    height: 64px;
  }

  .success-checkmark-circle {
    stroke: #16a34a;
    stroke-width: 3;
    stroke-dasharray: 151;
    stroke-dashoffset: 151;
    animation: circleDraw 0.4s ease forwards;
  }

  .success-checkmark-check {
    stroke: #16a34a;
    stroke-width: 4;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-dasharray: 36;
    stroke-dashoffset: 36;
    animation: checkDraw 0.3s ease 0.35s forwards;
  }

  .success-toast-title {
    margin: 0;
    color: #0f172a;
    font-size: 1rem;
    font-weight: 750;
    text-align: center;
  }

  .success-toast-message {
    margin: 0;
    color: #475569;
    font-size: 0.88rem;
    font-weight: 600;
    text-align: center;
  }

  @keyframes circleDraw {
    to {
      stroke-dashoffset: 0;
    }
  }

  @keyframes checkDraw {
    to {
      stroke-dashoffset: 0;
    }
  }

  @keyframes popIn {
    from {
      opacity: 0;
      transform: scale(0.85);
    }

    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.2s ease;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
</style>
