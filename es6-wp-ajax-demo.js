/**
 * ES6 WP Ajax Demo
 *
 * Uses ajax_es6_ajax_action() in es6-wp-ajax-demo.php
 *
 * @author Per Soderlind http://soderlind.no
 *
 */
document.addEventListener(
	"DOMContentLoaded",
	() => {
		// Wait util the webpage is loaded
		let button = document.getElementById("es6-demo-input"); // The form button
		let output = document.getElementById("es6-demo-output"); // The output area

		button.onclick = event = async (event) => {
			// Fire the event when the button is clicked.
			event.preventDefault();

			const self = event.currentTarget; // "this" button
			const data = new FormData();
			data.append("action", "es6_ajax_action"); // wp ajax action, more info at https://developer.wordpress.org/plugins/javascript/enqueuing/#ajax-action
			data.append("nonce", pluginES6WPAjax.nonce); // set the nonce, added at https://github.com/soderlind/es6-wp-ajax-demo/blob/master/es6-wp-ajax-demo.php#L75
			data.append("sum", self.dataset.sum); // get the value from the data-sum attribute in the form.

			const url = pluginES6WPAjax.ajaxurl; // set the ajax url, added at https://github.com/soderlind/es6-wp-ajax-demo/blob/master/es6-wp-ajax-demo.php#L76
			try {
				const response = await fetch(
					url,
					{
						// POST the data to WordPress
						method: "POST",
						credentials: "same-origin",
						body: data,
					},
				);

				const res = await response.json(); // read the json response from https://github.com/soderlind/es6-wp-ajax-demo/blob/master/es6-wp-ajax-demo.php#L57
				if (res.response === "success") {
					self.dataset.sum = res.data; // Update the data-sum attribute with the incremented value.
					output.innerHTML = res.data; // Display the updated value.
					console.log(res);
				} else {
					console.error(res);
				}
			} catch (err) {
				console.error(err);
			}
		};
	},
);
