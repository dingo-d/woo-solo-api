/**
 * Project Base overrides used in production and development build.
 *
 * @since 2.0.0
 */

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

module.exports = (options) => {
	// All Plugins used in production and development build.
	const plugins = [];

	// Clean public files before next build.
	if (!options.overrides.includes('cleanWebpackPlugin')) {
		plugins.push(new CleanWebpackPlugin({
			cleanStaleWebpackAssets: false,
		}));
	}

	// Output css from Js.
	if (!options.overrides.includes('miniCssExtractPlugin')) {
		plugins.push(new MiniCssExtractPlugin({
			filename: `${options.config.filesOutput}.css`,
		}));
	}

	// Create manifest.json file.
	if (!options.overrides.includes('manifestPlugin')) {
		plugins.push(new WebpackManifestPlugin({
			seed: {},
		}));
	}

	// All Optimizations used in production and development build.
	const optimization = {};

	if (!options.overrides.includes('runtimeChunk')) {
		optimization.runtimeChunk = false;
	}

	// All module used in production and development build.
	const module = {
		rules: [],
	};

	// Module for JS and JSX.
	if (!options.overrides.includes('js')) {
		module.rules.push({
			test: /\.(js|jsx)$/,
			exclude: /node_modules/,
			use: 'babel-loader',
		});
	}

	// Module for Scss.
	if (!options.overrides.includes('scss')) {
		module.rules.push({
			test: /\.scss$/,
			exclude: /node_modules/,
			use: [
				MiniCssExtractPlugin.loader,
				{
					loader: 'css-loader',
					options: {
						url: false,
					},
				},
				'postcss-loader', 'sass-loader', 'import-glob-loader',
			],
		});
	}

	return {
		optimization,
		plugins,
		module,
	};
};
