/* eslint-disable camelcase */
import React, { useState } from 'react';
import {ErrorNotice} from './ErrorNotice';

const {useSelect} = wp.data;
const {__} = wp.i18n;

const {
	Button,
	ExternalLink,
	TextareaControl,
	TextControl,
	CheckboxControl,
	PanelBody,
	PanelRow,
	Spinner,
	ToggleControl,
	SelectControl,
	Snackbar,
} = wp.components;

import {STORE_NAME} from "../store/store";

export const SettingsPanels = () => {

	const savedSettings = useSelect((select) => select(STORE_NAME).getSettings());
	const errors = useSelect((select) => select(STORE_NAME).getErrors());
	const isSaving = useSelect((select) => select(STORE_NAME).getIsSaving());

	const hasErrorClass = (type) => {
		return errors?.hasOwnProperty(type) ? 'has-error' : '';
	}

	// Why doesn't this work?
	const [settings, setSettings] = useState(savedSettings);

	return (
		<>
			<PanelBody
				title={__('Solo API token', 'woo-solo-api')}
				initialOpen={true}
			>
				<PanelRow>
					<TextControl
						className={`components-base-control__input ${hasErrorClass('solo_api_token')}`}
						name='solo_api_token'
						label={__('Solo API token', 'woo-solo-api')}
						help={__('Enter your personal token that you obtained from your SOLO account', 'woo-solo-api')}
						type='text'
						disabled={isSaving}
						value={settings.solo_api_token}
						onChange={value => setSettings(settings.solo_api_token = value)}
					/>
					<ErrorNotice type={'solo_api_token'} />
				</PanelRow>
			</PanelBody>
		</>
	)
}
