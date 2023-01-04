/**
 * Project Production config used only in production build.
 *
 * @since 2.0.0
 */

const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

const webpack = require('webpack');

module.exports = (options) => {
	// All Plugins used in production build.
	const plugins = [
		new webpack.ProvidePlugin({
			process: 'process/browser',
		}),
		new webpack.ProvidePlugin({
			Buffer: [ 'buffer', 'Buffer' ],
		}),
	];

	// All Optimizations used in production build.
	const optimization = {
		minimizer: [],
	};

	// Plugin used to minify output.
	if (! options.overrides.includes('terserPlugin')) {
		optimization.minimizer.push(new TerserPlugin({
			parallel: true,
			terserOptions: {
				output: {
					comments: false,
				},
			},
		}));
	}

	if (! options.overrides.includes('optimizeCSSAssetsPlugin')) {
		optimization.minimizer.push(new CssMinimizerPlugin({
				minimizerOptions: {
					preset: [
						"default",
						{
							discardComments: {removeAll: true},
						},
					],
				},
			}),
		);
	}

	return {
		plugins,
		optimization,
	};
};
