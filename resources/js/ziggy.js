const Ziggy = {
  url: 'http:\/\/localhost',
  port: null,
  defaults: {},
  routes: {
    home: { uri: '\/', methods: ['GET', 'HEAD'] },
    'admin.index': { uri: 'admin', methods: ['GET', 'HEAD'] },
    'loket.index': { uri: 'loket', methods: ['GET', 'HEAD'] },
    'loket.pasien': { uri: 'loket\/pasien', methods: ['GET', 'HEAD'] },
    'loket.search': { uri: 'loket\/search', methods: ['GET', 'HEAD'] },
    'templete.button': { uri: 'templete\/button', methods: ['GET', 'HEAD'] },
    'templete.form': { uri: 'templete\/form', methods: ['GET', 'HEAD'] },
    'templete.table': { uri: 'templete\/table', methods: ['GET', 'HEAD'] },
    'templete.card': { uri: 'templete\/card', methods: ['GET', 'HEAD'] },
    'templete.pagination': { uri: 'templete\/pagination', methods: ['GET', 'HEAD'] },
    'laporan.loket': { uri: 'laporan\/loket', methods: ['GET', 'HEAD'] },
    'laporan.rujukan': { uri: 'laporan\/rujukan', methods: ['GET', 'HEAD'] },
    'laporan.umum': { uri: 'laporan\/umum', methods: ['GET', 'HEAD'] },
    'laporan.gigi': { uri: 'laporan\/gigi', methods: ['GET', 'HEAD'] },
    'laporan.kia': { uri: 'laporan\/kia', methods: ['GET', 'HEAD'] },
    'laporan.lab': { uri: 'laporan\/lab', methods: ['GET', 'HEAD'] },
    'laporan.kb': { uri: 'laporan\/kb', methods: ['GET', 'HEAD'] },
    'laporan.ugd': { uri: 'laporan\/ugd', methods: ['GET', 'HEAD'] },
    'laporan.rawat-inap': { uri: 'laporan\/rawat-inap', methods: ['GET', 'HEAD'] },
    'laporan.sanitasi': { uri: 'laporan\/sanitasi', methods: ['GET', 'HEAD'] },
    'laporan.kunjungan-sehat': { uri: 'laporan\/kunjungan-sehat', methods: ['GET', 'HEAD'] },
    'mal-sehat.index': { uri: 'mal-sehat', methods: ['GET', 'HEAD'] },
    'mal-sehat.kesling.index': { uri: 'mal-sehat\/kesling', methods: ['GET', 'HEAD'] },
    'mal-sehat.kesling.konseling': {
      uri: 'mal-sehat\/kesling\/konseling-sanitasi',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesling.haji': {
      uri: 'mal-sehat\/kesling\/pengukuran-kebugaran-haji',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesling.anak': {
      uri: 'mal-sehat\/kesling\/pengukuran-kebugaran-anak',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesga.index': { uri: 'mal-sehat\/kesga', methods: ['GET', 'HEAD'] },
    'mal-sehat.kesga.konselingcatin': {
      uri: 'mal-sehat\/kesga\/konselingcatin',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesga.konselinghaji': {
      uri: 'mal-sehat\/kesga\/konselinghaji',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesga.konselingimunisasi': {
      uri: 'mal-sehat\/kesga\/konselingimunisasi',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesga.konselinganak': {
      uri: 'mal-sehat\/kesga\/konselinganak',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesga.konselingibu': {
      uri: 'mal-sehat\/kesga\/konselingibu',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesga.konselingkb': {
      uri: 'mal-sehat\/kesga\/konselingkb',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesga.konsultasigizi': {
      uri: 'mal-sehat\/kesga\/konsultasigizi',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.kesga.konsultasilansia': {
      uri: 'mal-sehat\/kesga\/konsultasilansia',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.ptm.index': { uri: 'mal-sehat\/ptm', methods: ['GET', 'HEAD'] },
    'mal-sehat.ptm.konselingberhentimerokok': {
      uri: 'mal-sehat\/ptm\/konselingberhentimerokok',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.ptm.skriningfaktorrisiko': {
      uri: 'mal-sehat\/ptm\/skriningfaktorrisiko',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.p3m.index': { uri: 'mal-sehat\/p3m', methods: ['GET', 'HEAD'] },
    'mal-sehat.p3m.konselinghivaids': {
      uri: 'mal-sehat\/p3m\/konselinghivaids',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.p3m.konselinglroa': {
      uri: 'mal-sehat\/p3m\/konselinglroa',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.p3m.konselingtb': { uri: 'mal-sehat\/p3m\/konselingtb', methods: ['GET', 'HEAD'] },
    'mal-sehat.yankes-primer.index': { uri: 'mal-sehat\/yankes-primer', methods: ['GET', 'HEAD'] },
    'mal-sehat.yankes-primer.kunjungankonsultasitradisional': {
      uri: 'mal-sehat\/yankes-primer\/kunjungankonsultasitradisional',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.yankes-primer.kunjunganketerangansehat': {
      uri: 'mal-sehat\/yankes-primer\/kunjunganketerangansehat',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.farmasi.index': { uri: 'mal-sehat\/farmasi', methods: ['GET', 'HEAD'] },
    'mal-sehat.farmasi.permintaanobat': {
      uri: 'mal-sehat\/farmasi\/permintaanobat',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.biakes.index': { uri: 'mal-sehat\/biakes', methods: ['GET', 'HEAD'] },
    'mal-sehat.biakes.pembiayaanjaminansehat': {
      uri: 'mal-sehat\/biakes\/pembiayaanjaminansehat',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.promkes.index': { uri: 'mal-sehat\/promkes', methods: ['GET', 'HEAD'] },
    'mal-sehat.promkes.kesehatanpeduliremaja': {
      uri: 'mal-sehat\/promkes\/kesehatanpeduliremaja',
      methods: ['GET', 'HEAD'],
    },
    'mal-sehat.home-visit': { uri: 'mal-sehat\/home-visit', methods: ['GET', 'HEAD'] },
    'mal-sehat.sehat': { uri: 'mal-sehat\/sehat', methods: ['GET', 'HEAD'] },
    'mal-sehat.rapid-test': { uri: 'mal-sehat\/rapid-test', methods: ['GET', 'HEAD'] },
    'storage.local': {
      uri: 'storage\/{path}',
      methods: ['GET', 'HEAD'],
      wheres: { path: '.*' },
      parameters: ['path'],
    },
  },
};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
