import TableIndex from "./components/TableIndex.vue";
import StepPelanggan from "./components/StepPelanggan.vue";
import DetailPelanggan from "./components/DetailPelanggan.vue";

const locales = window.AsgardCMS.locales;

export default [
	// pelanggan aktif
	{
		path: '/pelanggan',
		name: 'admin.pelanggan.pelanggans.index',
		component: TableIndex
	},
	// sales order

	{
		path: '/pelanggan/form/step',
		name: 'admin.pelanggan.form.step',
		component: StepPelanggan
	},

	{
		path: '/pelanggan/form/detail/:pelanggan_id',
		name: 'admin.pelanggan.form.detail',
		component: DetailPelanggan
	}
];