
/**
 * This is a main entry point for Webpack config.
 *
 * @param {string} env Current environment.
 * @param {Object} argv Arguments object.
 * @since 2.0.0
 */
module.exports = (env, argv) => {
	const projectConfig = {
		config: {
			projectDir: __dirname, // Current project directory absolute path.
			projectPath: 'wp-content/plugins/woo-solo-api', // Project path relative to project root.
		},
		resolve: {
			fallback: {
				assert: require.resolve('assert'),
			},
		},
		node: {
			Buffer: false,
			process: false,
		},
	};

	// Generate webpack config for this project using options object.
	const project = require('./webpack')(argv.mode, projectConfig);

	return {
		...project,
	};
};
