/* eslint-disable camelcase, no-unused-vars */
/* global wp */
/* eslint operator-linebreak: ["error", "after"] */

import {createInterpolateElement, useRef} from '@wordpress/element';
import {useSelect, useDispatch} from '@wordpress/data';
import {__} from '@wordpress/i18n';

import {
	TextareaControl,
	TextControl,
	PanelBody,
	PanelRow,
} from '@wordpress/components';

import {ErrorNotice} from '../ErrorNotice';

// Store
import {STORE_NAME} from '../../store/store';

export const EmailSettingsPanel = () => {
	const settings = useSelect((select) => select(STORE_NAME).getSettings());
	const errors = useSelect((select) => select(STORE_NAME).getErrors());
	const isActive = useSelect((select) => select(STORE_NAME).getIsActive());

	const {setSettings} = useDispatch(STORE_NAME);

	const hasErrorClass = (type) => {
		return errors?.hasOwnProperty(type) ? 'has-error' : '';
	};

	const settingsRefs = useRef({});

	return (
		<PanelBody
			title={__('Email Settings', 'woo-solo-api')}
			initialOpen={false}
		>
			<PanelRow>
				<TextControl
					ref={(ref) => settingsRefs.current.solo_api_mail_title = ref}
					className={`components-base-control__input ${hasErrorClass('solo_api_mail_title')}`}
					name='solo_api_mail_title'
					label={__('Set the title of the mail that will be send with the PDF invoice', 'woo-solo-api')}
					type='text'
					disabled={isActive}
					value={settings.solo_api_mail_title}
					onChange={(value) => setSettings({...settings, solo_api_mail_title: value, settingsRefs})}
				/>
				<ErrorNotice errors={errors} type={'solo_api_mail_title'} />
			</PanelRow>
			<PanelRow>
				<TextareaControl
					ref={(ref) => settingsRefs.current.solo_api_message = ref}
					className={`components-base-control__textarea ${hasErrorClass('solo_api_message')}`}
					name='solo_api_message'
					label={__('Type the message that will appear on the mail with the invoice PDF attached', 'woo-solo-api')}
					rows='10'
					disabled={isActive}
					value={settings.solo_api_message}
					onChange={(value) => setSettings({...settings, solo_api_message: value, settingsRefs})}
				/>
				<ErrorNotice errors={errors} type={'solo_api_message'} />
			</PanelRow>
			<PanelRow>
				<TextControl
					ref={(ref) => settingsRefs.current.solo_api_change_mail_from = ref}
					className={`components-base-control__input ${hasErrorClass('solo_api_change_mail_from')}`}
					name='solo_api_change_mail_from'
					label={__('Change the \'from\' name that shows when WordPress sends the mail', 'woo-solo-api')}
					help={
						createInterpolateElement(
							__('<str>CAUTION</str>: This change is global, every mail send from your WordPress will have this \'from\' name', 'woo-solo-api'),
							{
								str: <strong/>,
							} // eslint-disable-line
						)
					}
					type='text'
					disabled={isActive}
					value={settings.solo_api_change_mail_from}
					onChange={(value) => setSettings({...settings, solo_api_change_mail_from: value, settingsRefs})}
				/>
				<ErrorNotice errors={errors} type={'solo_api_change_mail_from'} />
			</PanelRow>
		</PanelBody>
	);
};
