<script>
import { Pie, mixins } from "vue-chartjs";
export default {
	extends: Pie,
	props: ["chartData", "options"],
	mixins: [mixins.reactiveProp],
	mounted() {
		this.renderChart(this.chartData, this.options);
	}
};
</script>