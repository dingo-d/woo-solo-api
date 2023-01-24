/* eslint-disable camelcase, no-unused-vars */
/* global wp */
/* eslint operator-linebreak: ["error", "after"] */

const {useRef} = wp.element;
const {useSelect, useDispatch} = wp.data;
const {__} = wp.i18n;

const {
	TextControl,
	PanelBody,
	PanelRow,
} = wp.components;

import {ErrorNotice} from '../ErrorNotice';

// Store
import {STORE_NAME} from '../../store/store';

export const TokenPanel = () => {
	const settings = useSelect((select) => select(STORE_NAME).getSettings());
	const errors = useSelect((select) => select(STORE_NAME).getErrors());
	const isSaving = useSelect((select) => select(STORE_NAME).getIsSaving());

	const {setSettings, setErrors} = useDispatch(STORE_NAME);

	const hasErrorClass = (type) => {
		return errors?.hasOwnProperty(type) ? 'has-error' : '';
	};

	const settingsRefs = useRef({});

	return (
		<PanelBody
			title={__('Solo API token', 'woo-solo-api')}
			initialOpen={true}
		>
			<PanelRow>
				<TextControl
					ref={(ref) => settingsRefs.current.solo_api_token = ref}
					className={`components-base-control__input ${hasErrorClass('solo_api_token')}`}
					name='solo_api_token'
					label={__('Solo API token', 'woo-solo-api')}
					help={__('Enter your personal token that you obtained from your SOLO account', 'woo-solo-api')}
					type='password'
					disabled={isSaving}
					value={settings.solo_api_token}
					onChange={(value) => setSettings({...settings, solo_api_token: value, settingsRefs})}
				/>

				<ErrorNotice errors={errors} type={'solo_api_token'} />
			</PanelRow>
		</PanelBody>
	);
};
