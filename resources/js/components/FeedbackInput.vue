<template>
<div class="container">
	<div v-if="status != 'SUCCESS'">
		<div class="row mt-5">
			<div class="col-md-8 offset-md-2 text-center">
				<h1>
				Gambarkan dengan 3
				kata, Bagaimana Visi anda terhadap
				BPN pada tahun
				2020.
				</h1>
			</div>
		</div>
		<div class="row mt-5">
			<div class="col-md-6 offset-md-3">
				<input type="text" class="form-control form-control-lg" placeholder="contoh: keren top mantab" v-bind:value="feedback" v-on:input="feedback = $event.target.value" />
				<br />
				<button :disabled="status == 'PROCESSING'" v-on:click="submit" class="btn btnprimary btn-block btn-lg" >
				Submit
				</button>
				<br />
				<div v-if="status == 'ERROR'" class="alert alert-danger">
					{{ message }}
				</div>
			</div>
		</div>
	</div>
	<div class="mt-5" v-if="status == 'SUCCESS'">
		<div class="row">
			<div class="col-md-6 offset-md-3">
				<div class="alert alert-success">{{ message }}</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import axios from "axios";

export default {
	data() {
		return {
			feedback: "",
			status: "IDLE", // IDLE | SUBMITTING | ERROR | SUCCESS
			message: ""
		};
	},

	methods: {
		submit: function() {
		this.$data.status = "SUBMITTING";

	axios.post("/api/v1/feedback", {
		words: this.$data.feedback
	})
		.then(response => {
		this.$data.status = "SUCCESS";
		this.$data.message = "Terima kasih atas partisipasi Anda!";
		})

		.catch(error => {
			this.$data.status = "ERROR";
			if (error.response.data.message) {
				this.$data.message = error.response.data.message;
			} else {
				this.$data.message = "Terjadi kesalahan saat menyimpan feedback";
			}
		});
		}
	},
mounted() {}
};
</script>