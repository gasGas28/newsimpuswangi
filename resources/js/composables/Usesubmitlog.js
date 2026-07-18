import { ref } from 'vue';

/**
 * useSubmitLog
 *
 * Composable untuk mengelola log pengiriman data ke SATUSEHAT.
 *
 * @param {string} storageKey - Kunci unik localStorage per jenis data + idSkrining
 *
 * @example
 * const log = useSubmitLog(`hipertensi_logs_${props.DataPasien?.idSkrining}`)
 */
export function useSubmitLog(storageKey) {
  const MAX_LOGS = 50;

  // ─── Helpers ─────────────────────────────────────────────────
  function generateId() {
    return `${Date.now()}-${Math.random().toString(36).slice(2, 7)}`;
  }

  function nowLabel() {
    return new Date().toLocaleString('id-ID', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
    });
  }

  // ─── Persistensi ─────────────────────────────────────────────
  function saveLogs(list) {
    try {
      localStorage.setItem(storageKey, JSON.stringify(list));
    } catch {
      if (list.length > 1) saveLogs(list.slice(0, -1));
    }
  }

  function loadLogs() {
    try {
      const raw = localStorage.getItem(storageKey);
      if (!raw) return [];
      const parsed = JSON.parse(raw);
      return Array.isArray(parsed) ? parsed : [];
    } catch {
      return [];
    }
  }

  // ─── State ───────────────────────────────────────────────────
  const logs = ref(loadLogs());
  const isSending = ref(false);
  const lastStatus = ref(null); // 'success' | 'error' | null
  const statusMessage = ref('');

  // ─── Aksi Log ────────────────────────────────────────────────
  function addLog(entry) {
    const id = generateId();
    logs.value = [{ id, showDetail: false, ...entry }, ...logs.value].slice(0, MAX_LOGS);
    saveLogs(logs.value);
    return id;
  }

  function updateLog(id, patch) {
    const idx = logs.value.findIndex((l) => l.id === id);
    if (idx === -1) return;
    logs.value[idx] = { ...logs.value[idx], ...patch };
    saveLogs(logs.value);
  }

  function clearLogs() {
    if (!confirm('Yakin ingin menghapus semua log pengiriman?')) return;
    logs.value = [];
    localStorage.removeItem(storageKey);
  }

  // ─── Single submit ────────────────────────────────────────────
  /**
   * Kirim satu request dan catat hasilnya ke log.
   * Mengembalikan Promise<boolean> — true jika sukses, false jika gagal.
   *
   * @param {object} step
   * @param {Function} step.routeFn     - () => string URL
   * @param {string}  step.logTitle     - Judul entri log
   * @param {string}  [step.idField]    - Nama field ID dari flash.data
   * @param {Function} step.routerPost  - router.post dari Inertia
   * @param {Function} step.getFlash    - () => page.props.flash
   */
  function submitOne(step) {
    const { routeFn, logTitle, idField = 'id', routerPost, getFlash } = step;

    return new Promise((resolve) => {
      const timestamp = nowLabel();
      let settled = false;

      const pendingId = addLog({
        status: 'pending',
        statusLabel: 'Mengirim',
        title: logTitle,
        timestamp,
        message: 'Menunggu respons dari server SATUSEHAT...',
        resourceId: null,
        httpStatus: null,
        detail: null,
      });

      routerPost(
        routeFn(),
        {},
        {
          preserveScroll: true,
          showGlobalLoader: false,
          only:['DataPasien'],
          onSuccess: () => {
            settled = true;
            const f = getFlash();
            updateLog(pendingId, {
              status: 'success',
              statusLabel: 'Berhasil',
              message: f?.message ?? 'Data berhasil dikirim ke SATUSEHAT.',
              resourceId: f?.data?.[idField] ?? f?.data?.id ?? null,
              httpStatus: 200,
              detail: f?.data ? JSON.stringify(f.data, null, 2) : null,
              showDetail: false,
            });
            resolve(true);
          },
          onError: (errors) => {
            settled = true;
            const errMsg =
              Object.values(errors).flat().join(' ') || 'Terjadi kesalahan saat pengiriman.';
            updateLog(pendingId, {
              status: 'error',
              statusLabel: 'Gagal',
              message: errMsg,
              httpStatus: null,
              detail: JSON.stringify(errors, null, 2),
              showDetail: false,
            });
            resolve(false);
          },
          onFinish: () => {
            if (settled) return;
            updateLog(pendingId, {
              status: 'error',
              statusLabel: 'Gagal',
              message: 'Request selesai tanpa respons yang dikenali.',
              showDetail: false,
            });
            resolve(false);
          },
        }
      );
    });
  }

  // ─── Sequential submit ────────────────────────────────────────
  /**
   * Kirim satu atau lebih request secara berurutan.
   * Jika salah satu langkah gagal, langkah berikutnya dibatalkan
   * dan dicatat sebagai 'cancelled' di log.
   *
   * @param {object}   options
   * @param {Array}    options.steps       - Array step ({ routeFn, logTitle, idField, routerPost, getFlash })
   * @param {Function} options.getFlash    - () => page.props.flash (shared)
   * @param {Function} options.routerPost  - router.post (shared)
   * @param {string}   [options.successMessage] - Pesan status bar saat semua sukses
   */
  async function submit({ steps, getFlash, routerPost, successMessage }) {
    if (isSending.value) return;

    isSending.value = true;
    lastStatus.value = null;
    statusMessage.value = '';

    let allSuccess = true;

    for (let i = 0; i < steps.length; i++) {
      const step = {
        getFlash,
        routerPost,
        ...steps[i], // step boleh override getFlash/routerPost jika perlu
      };

      const success = await submitOne(step);

      if (!success) {
        allSuccess = false;

        // Tandai semua langkah berikutnya sebagai dibatalkan
        for (let j = i + 1; j < steps.length; j++) {
          addLog({
            status: 'cancelled',
            statusLabel: 'Dibatalkan',
            title: steps[j].logTitle,
            timestamp: nowLabel(),
            message: `Dibatalkan karena langkah sebelumnya (${steps[i].logTitle}) gagal.`,
            resourceId: null,
            httpStatus: null,
            detail: null,
          });
        }
        break;
      }
    }

    isSending.value = false;
    lastStatus.value = allSuccess ? 'success' : 'error';
    statusMessage.value = allSuccess
      ? (successMessage ?? 'Semua data berhasil dikirim ke SATUSEHAT.')
      : 'Pengiriman gagal. Periksa log untuk detail.';
  }

  return {
    // State
    logs,
    isSending,
    lastStatus,
    statusMessage,
    // Aksi
    addLog,
    updateLog,
    clearLogs,
    submit,
    // Helper (jika komponen perlu)
    nowLabel,
  };
}
