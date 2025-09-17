import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
	plugins: [
		laravel({
			input: [
				'resources/css/app.css',
				'resources/js/app.js',
				'resources/js/central/tenancy-delete.js',
				'resources/js/central/tenancy-edit.js',
				'resources/js/central/header.js',
				'resources/js/central/notification.js',
			],
			refresh: true,
		}),
		tailwindcss(),
	],
});
