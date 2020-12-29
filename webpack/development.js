/**
 * Project Development config used only in development build.
 *
 * @since 2.0.0
 */

const webpack = require('webpack');

// Define developmentConfig setup.
module.exports = () => {
	// All Plugins used in development build.
	const plugins = [
		new webpack.ProvidePlugin({
			process: 'process/browser',
		}),
		new webpack.ProvidePlugin({
			Buffer: [ 'buffer', 'Buffer' ],
		}),
	];

	return {
		plugins,
		devtool: false,
	};
};
