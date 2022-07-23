/* eslint-disable camelcase */
import React, { useState } from 'react';

const {__} = wp.i18n;

export const SettingsPanels = () => {

	// Settings.
	const [solo_api_payment_type, setSolo_api_payment_type] = useState('');
	const [solo_api_token, setSolo_api_token] = useState('');
	const [solo_api_measure, setSolo_api_measure] = useState('1');
	const [solo_api_languages, setSolo_api_languages] = useState('1');
	const [solo_api_currency, setSolo_api_currency] = useState('1');
	const [solo_api_service_type, setSolo_api_service_type] = useState('0');
	const [solo_api_show_taxes, setSolo_api_show_taxes] = useState(false);
	const [solo_api_invoice_type, setSolo_api_invoice_type] = useState('1');
	const [solo_api_mail_title, setSolo_api_mail_title] = useState('');
	const [solo_api_message, setSolo_api_message] = useState('');
	const [solo_api_change_mail_from, setSolo_api_change_mail_from] = useState('');
	const [solo_api_enable_pin, setSolo_api_enable_pin] = useState(false);
	const [solo_api_enable_iban, setSolo_api_enable_iban] = useState(false);
	const [solo_api_due_date, setSolo_api_due_date] = useState('');
	const [solo_api_mail_gateway, setSolo_api_mail_gateway] = useState('a:0:{}');
	const [solo_api_send_pdf, setSolo_api_send_pdf] = useState(false);
	const [solo_api_send_control, setSolo_api_send_control] = useState('');
	const [solo_api_available_gateways, setSolo_api_available_gateways] = useState('a:0:{}');

	const [isApiRequestOver, setIsApiRequestOver] = useState(true);
	const [isSaving, setIsSaving] = useState(false);
	const [isSaved, setIsSaved] = useState(false);
	const [hasErrors, setHasErrors] = useState(false);
	const [errors, setErrors] = useState({});
	const [apiResponse, setApiResponse] = useState('');

	return (
		<></>
	)
}
