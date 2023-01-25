/* eslint-disable camelcase, no-unused-vars */
/* global wp, window */
/* eslint operator-linebreak: ["error", "after"] */

const {__} = wp.i18n;
const {useSelect} = wp.data;
const {Snackbar} = wp.components;

import {STORE_NAME} from '../store/store';

export const Popup = () => {
	const errors = useSelect((select) => select(STORE_NAME).getErrors());
	const hasErrors = Object.keys(errors).length > 0;

	const isActive = useSelect((select) => select(STORE_NAME).getIsActive());

	return (
		<Snackbar className={isActive ? 'is-visible' : 'is-hidden'}>
			{
				hasErrors ?
					__('Errors happened during saving', 'woo-solo-api') :
					__('Settings saved', 'woo-solo-api')
			}
		</Snackbar>
	);
};
