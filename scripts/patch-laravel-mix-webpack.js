const fs = require('fs');
const path = require('path');

const pluginPath = path.join(
    __dirname,
    '..',
    'node_modules',
    'laravel-mix',
    'src',
    'webpackPlugins',
    'BuildOutputPlugin.js'
);
const webpackPluginsPath = path.join(
    __dirname,
    '..',
    'node_modules',
    'laravel-mix',
    'src',
    'builder',
    'webpack-plugins.js'
);

if (!fs.existsSync(pluginPath)) {
    process.exit(0);
}

const oldImport = "const { formatSize } = require('webpack/lib/SizeFormatHelpers');";
const newImport = `let formatSize;

try {
    ({ formatSize } = require('webpack/lib/SizeFormatHelpers'));
} catch (error) {
    formatSize = require('webpack/lib/util/formatSize');
}`;

const source = fs.readFileSync(pluginPath, 'utf8');

if (source.includes(oldImport)) {
    fs.writeFileSync(pluginPath, source.replace(oldImport, newImport));
} else if (!source.includes(newImport)) {
    console.warn('Laravel Mix Webpack output patch was not applied; import line was not found.');
}

if (fs.existsSync(webpackPluginsPath)) {
    const progressSource = fs.readFileSync(webpackPluginsPath, 'utf8');
    const oldProgressBlock = `    if (process.env.NODE_ENV !== 'test') {
        plugins.push(new WebpackBar({ name: 'Mix' }));
    }
`;
    const newProgressBlock = `    // Webpack 5.109+ rejects the legacy webpackbar options used by Mix 6.
    // BuildOutputPlugin still reports successful builds, so the progress bar is skipped.
`;

    if (progressSource.includes(oldProgressBlock)) {
        fs.writeFileSync(
            webpackPluginsPath,
            progressSource.replace(oldProgressBlock, newProgressBlock)
        );
    }
}
