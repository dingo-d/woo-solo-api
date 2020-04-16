/* eslint-disable camelcase */
/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	render
} = wp.element;

render(
	<h1>Hello World</h1>,
	document.getElementById('solo-api-options-page')
);
