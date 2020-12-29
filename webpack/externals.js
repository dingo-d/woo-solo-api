/**
 * Return all global objects from window object.
 * Add all Block Editor external libs so you can use it like @wordpress/lib_name.
 *
 * @since 2.0.0
 */
function getExternals() {
	const ext = {};
	const wplib = [
		'components',
		'blocks',
		'element',
		'i18n',
	];
	wplib.forEach((name) => {
		ext[`@wordpress/${name}`] = `wp.${name}`;
	});

	ext['@wordpress/block-editor'] = 'wp.blockEditor';
	ext['@wordpress/api-fetch'] = 'wp.apiFetch';

	return ext;
}

module.exports = {
	externals: getExternals(),
};
