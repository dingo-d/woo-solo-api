/* eslint-disable camelcase, no-unused-vars */
/* global wp */
/* eslint operator-linebreak: ["error", "after"] */

import {useRef} from '@wordpress/element';
import {useSelect, useDispatch} from '@wordpress/data';
import {__} from '@wordpress/i18n';

import {
	CheckboxControl,
	PanelBody,
	PanelRow,
	ToggleControl,
	SelectControl,
} from '@wordpress/components';

import {ErrorNotice} from '../ErrorNotice';

// Store
import {STORE_NAME} from '../../store/store';

export const AdditionalSettingsPanel = () => {
	const settings = useSelect((select) => select(STORE_NAME).getSettings());
	const errors = useSelect((select) => select(STORE_NAME).getErrors());
	const isActive = useSelect((select) => select(STORE_NAME).getIsActive());

	const {setSettings} = useDispatch(STORE_NAME);

	const hasErrorClass = (type) => {
		return errors?.hasOwnProperty(type) ? 'has-error' : '';
	};

	const paymentGateways = settings.solo_api_available_gateways;

	const settingsRefs = useRef({});

	return (
		<PanelBody
			title={__('Additional Settings', 'woo-solo-api')}
			initialOpen={false}
		>
			<div className='components-panel__columns'>
				<div className='components-panel__column'>
					<PanelRow className='components-panel__row--single'>
						<h4>{__('WooCommerce checkout settings', 'woo-solo-api')}</h4>
						<ToggleControl
							ref={(ref) => settingsRefs.current.solo_api_enable_pin = ref}
							className={hasErrorClass('solo_api_enable_pin')}
							name='solo_api_enable_pin'
							label={__('Enable the PIN field on the billing and shipping from in the checkout', 'woo-solo-api')}
							help={settings.solo_api_enable_pin ? __('Enable', 'woo-solo-api') : __('Disable', 'woo-solo-api')}
							checked={settings.solo_api_enable_pin}
							disabled={isActive}
							onChange={() => setSettings({...settings, solo_api_enable_pin: ! settings.solo_api_enable_pin, settingsRefs})}
						/>
						<ErrorNotice errors={errors} type={'solo_api_enable_pin'} />
						<ToggleControl
							ref={(ref) => settingsRefs.current.solo_api_enable_iban = ref}
							className={hasErrorClass('solo_api_enable_iban')}
							name='solo_api_enable_iban'
							label={__('Enable the IBAN field on the billing and shipping from in the checkout', 'woo-solo-api')}
							help={settings.solo_api_enable_iban ? __('Enable', 'woo-solo-api') : __('Disable', 'woo-solo-api')}
							checked={settings.solo_api_enable_iban}
							disabled={isActive}
							onChange={() => setSettings({...settings, solo_api_enable_iban: ! settings.solo_api_enable_iban, settingsRefs})}
						/>
						<ErrorNotice errors={errors} type={'solo_api_enable_iban'} />
					</PanelRow>
				</div>
				<div className='components-panel__column'>
					<PanelRow className='components-panel__row--single'>
						<h4>{__('PDF settings', 'woo-solo-api')}</h4>
						<ToggleControl
							ref={(ref) => settingsRefs.current.solo_api_send_pdf = ref}
							className={hasErrorClass('solo_api_send_pdf')}
							name='solo_api_send_pdf'
							label={__('Send the email to the client with the PDF of the order or the invoice', 'woo-solo-api')}
							help={settings.solo_api_send_pdf ? __('Send email', 'woo-solo-api') : __('Don\'t send email', 'woo-solo-api')}
							checked={settings.solo_api_send_pdf}
							disabled={isActive}
							onChange={() => setSettings({...settings, solo_api_send_pdf: ! settings.solo_api_send_pdf, settingsRefs})}
						/>
						<ErrorNotice errors={errors} type={'solo_api_send_pdf'} />
					</PanelRow>
					{settings.solo_api_send_pdf &&
						<>
							<PanelRow className='components-panel__row--single'>
								<h4>{__('Send mail to selected payment gateways', 'woo-solo-api')}</h4>
								{
									Object.keys(paymentGateways).map((type) => {
										return <CheckboxControl
											ref={(ref) => settingsRefs.current.solo_api_mail_gateway = ref}
											className={`components-base-control__checkboxes ${hasErrorClass('solo_api_mail_gateway')}`}
											name={type}
											label={paymentGateways[type]}
											key={type}
											value={type}
											checked={settings.solo_api_mail_gateway.includes(type)}
											onChange={(check) => setSettings({
												...settings,
												solo_api_mail_gateway: check ?
													[...settings.solo_api_mail_gateway, type] :
													settings.solo_api_mail_gateway.filter((el) => el !== type), settingsRefs})}
										/>;
									})
								}
								<ErrorNotice errors={errors} type={'solo_api_mail_gateway'} />
							</PanelRow>
							<PanelRow className='components-panel__row--single'>
								<h4>{__('PDF send control', 'woo-solo-api')}</h4>
								<p className='components-base-control__notice'>{__('Decide when to send the PDF of the order or invoice.' +
									'On customer checkout, or when you approve the order in the WooCommerce admin.' +
									'This will determine when the call to the SOLO API will be made', 'woo-solo-api')}</p>
								<SelectControl
									ref={(ref) => settingsRefs.current.solo_api_send_control = ref}
									className={hasErrorClass('solo_api_send_control')}
									name='solo_api_send_control'
									label={__('Send on:', 'woo-solo-api')}
									disabled={isActive}
									value={settings.solo_api_send_control}
									onChange={(value) => setSettings({...settings, solo_api_send_control: value, settingsRefs})}
									options={[
										{value: 'checkout', label: __('Checkout', 'woo-solo-api')},
										{
											value: 'status_change',
											label: __('Status change to \'completed\'', 'woo-solo-api'),
										},
									]}
								/>

								<ErrorNotice errors={errors} type={'solo_api_send_control'} />
							</PanelRow>
						</>
					}
				</div>
			</div>
		</PanelBody>
	);
};
