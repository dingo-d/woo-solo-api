import { wp_globals } from '@kucrut/vite-for-wp/utils';
import { readFileSync } from 'node:fs';
import create_config from '@kucrut/vite-for-wp';
import external_globals from 'rollup-plugin-external-globals';
import inject from '@rollup/plugin-inject';


export default create_config('assets/dev/application.jsx', 'assets/public', {
	plugins: [
		external_globals({
			...wp_globals()
		}),
	],
	server: {
		host: 'woo-solo-test.test',
		https: {
			cert: readFileSync('/Users/deniszoljom/.config/valet/Certificates/woo-solo-test.test.crt'),
			key: readFileSync('/Users/deniszoljom/.config/valet/Certificates/woo-solo-test.test.key'),
		},
	},
	build: {
		rollupOptions: {
			plugins: [inject({Buffer: ['buffer', 'Buffer']})],
		},
	},
});
