const fs = require( 'fs' );
const path = require( 'path' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const sharedConfig = {
	...defaultConfig,
	output: {
		path: path.resolve( process.cwd(), 'build' ),
		filename: '[name].js',
		chunkFilename: '[name].js',
	},
	plugins: [
		...defaultConfig.plugins,
		new RemoveEmptyScriptsPlugin(),
	],
	optimization: {
		...defaultConfig.optimization,
		minimizer: defaultConfig.optimization.minimizer.concat( [ new CssMinimizerPlugin() ] ),
	},
};

const styles = {
	...sharedConfig,
	output: {
		path: path.resolve( process.cwd(), 'build' ),
		filename: '[name].js',
		chunkFilename: '[name].js',
	},
	entry: () => {
		const entries = {};
		const dir = './assets/src/css';
		fs.readdirSync( dir ).forEach( ( fileName ) => {
			const fullPath = `${ dir }/${ fileName }`;
			if (
				! fs.lstatSync( fullPath ).isDirectory() &&
				fileName.match( /\.css$/ )
			) {
				entries[ `css/${ fileName.replace( /\.[^/.]+$/, '' ) }` ] = fullPath;
			}
		} );
		return entries;
	},
	plugins: [
		...sharedConfig.plugins.filter(
			( plugin ) => plugin.constructor.name !== 'DependencyExtractionWebpackPlugin',
		),
	],
};

const scripts = {
	...sharedConfig,
	entry: {
		'js/fancybox_admin': path.resolve( process.cwd(), 'assets', 'src', 'js', 'fancybox_admin.js' ),
		'js/caller': path.resolve( process.cwd(), 'assets', 'src', 'js', 'caller.js' ),
		'js/jquery.colorbox': path.resolve( process.cwd(), 'assets', 'src', 'js', 'jquery.colorbox.js' ),
		'blocks/video-lightbox': path.resolve( process.cwd(), 'assets', 'src', 'blocks', 'video-lightbox', 'index.js' ),
	},
	module: {
		rules:
			sharedConfig?.module?.rules?.filter( ( rule ) => {
				return (
					! rule.test ||
					( ! rule.test.toString().includes( 'css' ) )
				);
			} ) || [],
	},
	resolve: {
		...sharedConfig.resolve,
		extensions: [ '.js', '.jsx' ],
		alias: {
			...( sharedConfig.resolve?.alias || {} ),
			'@': path.resolve( process.cwd(), 'assets', 'src' ),
		},
	},
};

module.exports = [
	scripts,
	styles,
];
