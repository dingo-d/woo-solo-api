/* eslint-disable camelcase */
const {useSelect} = wp.data;
const {__} = wp.i18n;

import {STORE_NAME} from "../store/store";

export const ErrorNotice = (props) => {
	const {type} = props;
	const errors = useSelect((select) => select(STORE_NAME).getErrors());

	return (
		<>
			{errors?.hasOwnProperty(type) &&
				<p className='components-base-control__error'>
					{__('Validation error: ', 'woo-solo-api') + errors[type]}
				</p>
			}
		</>
	)
}
