/* eslint-disable camelcase, no-unused-vars */
/* global wp, window */
/* eslint operator-linebreak: ["error", "after"] */

import {__} from '@wordpress/i18n';
import {useSelect} from '@wordpress/data';
import {Snackbar} from '@wordpress/components';

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
