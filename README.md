# WordPress Ajax using native JavaScript


## Prerequisite

You should know [how WordPress does Ajax](https://developer.wordpress.org/plugins/javascript/ajax/).

## Look at the code

I recommend that you [take a look at the code](https://github.com/soderlind/es6-wp-ajax-demo/blob/master/es6-wp-ajax-demo.js), it's not hard to understand what's happening.

### JavaScript (ES6)

First I create the `data` object using [FormData](https://javascript.info/formdata).

```javascript
const data = new FormData();
data.append('action', 'es6_ajax_action');
data.append('nonce', pluginES6WPAjax.nonce);
data.append('sum', self.dataset.sum);
```

Then I use [aync/await](https://javascript.info/async-await) with [fetch](https://javascript.info/fetch) to do the ajax call.

```javascript
const response = await fetch(url, {
	method: 'POST',
	credentials: 'same-origin',
	body: data
});

const res = await response.json();
if (res.response == 'success') {
	self.dataset.sum = res.data;
	output.innerHTML = res.data;
	console.log(res);
} else {
	console.error(res);
}
```

Note: [previous release](https://github.com/soderlind/es6-wp-ajax-demo/releases/tag/1.0.2) use [fetch().then().catch()](https://github.com/soderlind/es6-wp-ajax-demo/blob/1.0.2/es6-wp-ajax-demo.js#L23-L39)


Note 2: Why move from `fetch().then().catch()` to `async/await`? Because V8 ..

> [favor async functions and await over hand-written promise code](https://v8.dev/blog/fast-async#conclusion)
> - V8 dev blog


### PHP

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

