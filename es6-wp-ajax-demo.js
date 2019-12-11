/**
 * ES6 WP Ajax Demo
 *
 * Uses ajax_es6_ajax_action() in es6-wp-ajax-demo.php
 *
 * @author Per Soderlind http://soderlind.no
 *
 */
document.addEventListener('DOMContentLoaded', e => {
	let button = document.getElementById('es6-demo-input');
	let output = document.getElementById('es6-demo-output');

	button.onclick = event => {
		event.preventDefault();

		const self = event.currentTarget;
		const data = new FormData();
		data.append('action', 'es6_ajax_action');
		data.append('nonce', pluginES6WPAjax.nonce);
		data.append('sum', self.dataset.sum);

		const url = pluginES6WPAjax.ajaxurl + '?now=' + escape(new Date().getTime().toString());
		fetch(url, {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		}).then(res => res.json())
			.then(res => {
				if (res.response == 'success') {
					self.dataset.sum = res.data;
					output.innerHTML = res.data;
				} else {
					console.error(res);
				}
			})
			.catch(err => {
				console.error(err);
			})
	};
});
