/* eslint-disable import/no-dynamic-require, global-require */

/**
 * This is a main entry point for Webpack config.
 *
 * @since 2.0.0
 */
module.exports = (env, argv) => {

	const projectConfig = {
		config: {
			projectDir: __dirname, // Current project directory absolute path.
			projectPath: 'wp-content/plugins/woo-solo-api', // Project path relative to project root.
		},
	};

	// Generate webpack config for this project using options object.
	const project = require('./webpack')(argv.mode, projectConfig);

	return {
		...project,
	};
};
