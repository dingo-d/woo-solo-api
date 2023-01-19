/* eslint-disable camelcase */
const {__} = wp.i18n;
export const ErrorNotice = ({errors, type}) => {
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
