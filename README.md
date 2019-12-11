# Using ES6 to do a WP Ajax call

## Prerequisite

You should know [how WordPress does Ajax](https://developer.wordpress.org/plugins/javascript/ajax/).

## PHP

The PHP code is more or less the same as you would do when using jQuery, but I've added the `fetch` [polyfill](https://en.wikipedia.org/wiki/Polyfill_(programming))

```php
// Load the fetch polyfill, url via https://polyfill.io/v3/url-builder/.
wp_enqueue_script( 'polyfill-fetch',
	'https://polyfill.io/v3/polyfill.min.js?features=fetch',
	[],
	ES6_WP_AJAX_DEMO_VERSION,
	true
);
```

## ES6

First I create the `data` object using [FormData](https://javascript.info/formdata).

```javascript
const data = new FormData();
data.append('action', 'es6_ajax_action');
data.append('nonce', pluginES6WPAjax.nonce);
data.append('sum', self.dataset.sum);
```

Then I use [fetch](https://javascript.info/fetch) to do the ajax call.

```javascript
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
```

## Demo

Not very exciting, the demo increments a number when you click on a button.

## Installation

- [Download the plugin](https://github.com/soderlind/es6-wp-ajax-demo/archive/master.zip)
- Install and activate the plugin.
- Add the `[es6demo]` shortcode to a page.
- Click on the `+` button to increment the number.

## Copyright and License

es6-wp-ajax-demo is copyright 2019 Per Soderlind

es6-wp-ajax-demo is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

es6-wp-ajax-demo is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with the Extension. If not, see http://www.gnu.org/licenses/.

