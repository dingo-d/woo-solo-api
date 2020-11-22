/* eslint-disable import/no-extraneous-dependencies*/

/**
 * Project Development config used only in development build.
 *
 * @since 2.0.0
 */

// Define developmentConfig setup.
module.exports = (options) => {

  // All Plugins used in development build.
  const plugins = [];

  return {
    plugins,

    devtool: false,
  };
};
