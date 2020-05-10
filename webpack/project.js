/**
 * Project config used only in development and production build.
 *
 * @since 2.0.0
 */

const fs = require('fs');

module.exports = (options) => {
  const entry = {};
  const output = {
    path: options.config.outputPath,
    publicPath: options.config.publicPath,
  };

  // Load application entry point.
  if (!options.overrides.includes('application') && fs.existsSync(options.config.applicationEntry)) {
    entry.application = options.config.applicationEntry;
  }

  // Load filename Output.
  if (!options.overrides.includes('filename')) {
    output.filename = `${options.config.filesOutput}.js`;
  }

  return {
    entry,
    output,
  };
};
