/* eslint-disable valid-typeof */
const packageJson = require('./../package.json');

/**
 * File holding webpack utility helpers
 *
 * @since 2.0.0
 */

const path = require('path');

/**
 * Generate all paths required for Webpack build to work.
 *
 * @param {string} projectDir Current project directory absolute path.
 * @param {string} projectPathConfig Project path relative to project root.
 * @param {string} assetsPathConfig Assets path after projectPath location.
 * @param {string} outputPathConfig Public output path after projectPath location.
 *
 * @since 2.0.0
 */
function getConfig(projectDir, projectPathConfig, assetsPathConfig = 'assets/dev', outputPathConfig = 'assets/public') {

	if (typeof projectDir === 'undefined') {
		throw 'projectDir parameter is empty, please provide it. This key represents the current project directory absolute path. For example: __dirname'; 	// eslint-disable-line no-throw-literal
	}

	if (typeof projectPathConfig === 'undefined') {
		throw 'projectPath parameter is empty, please provide it. This key represents the project path relative to project root.'; 	// eslint-disable-line no-throw-literal
	}

	// Clear all slashes from user config.
	const projectPathConfigClean = projectPathConfig.replace(/^\/|\/$/g, '');
	const assetsPathConfigClean = assetsPathConfig.replace(/^\/|\/$/g, '');
	const outputPathConfigClean = outputPathConfig.replace(/^\/|\/$/g, '');

	// Create absolute path from the projects relative path.
	const absolutePath = `${projectDir}`;

	return {
		absolutePath,

		// Output files absolute location.
		outputPath: path.resolve(absolutePath, outputPathConfigClean),

		// Output files relative location, added before every output file in manifest.json. Should start and end with "/".
		publicPath: path.join('/', projectPathConfigClean, outputPathConfigClean, '/'),

		// Source files entries absolute locations.
		applicationEntry: path.resolve(absolutePath, assetsPathConfigClean, 'application.js'),
	};
}

/**
 * Generate project paths for node_modules and libs root path.
 *
 * @param {string} nodeModules Path to node modules. In general it is __dirname.
 *
 * @since 2.0.0
 */
function getPackagesPath(nodeModules) {
	return {
		nodeModulesPath: path.resolve(nodeModules, 'node_modules'),
	};
}

module.exports = {
	getConfig,
	getPackagesPath,
};
