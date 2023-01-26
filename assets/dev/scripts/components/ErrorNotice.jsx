/* eslint-disable camelcase */
/* global wp */

import {__} from '@wordpress/i18n';
import {useSelect} from '@wordpress/data';

import {STORE_NAME} from '../store/store';

export const ErrorNotice = ({type}) => {
	const errors = useSelect((select) => select(STORE_NAME).getErrors());

	return (
		<>
			{errors?.hasOwnProperty(type) &&
				<p className='components-base-control__error'>
					{__('Validation error: ', 'woo-solo-api') + errors[type]}
				</p>
			}
		</>
	);
};
