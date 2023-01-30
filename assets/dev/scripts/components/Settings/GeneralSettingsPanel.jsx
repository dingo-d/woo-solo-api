/* eslint-disable camelcase, no-unused-vars */
/* global wp */
/* eslint operator-linebreak: ["error", "after"] */

import {createInterpolateElement, useRef} from '@wordpress/element';
import {useSelect, useDispatch} from '@wordpress/data';
import {__} from '@wordpress/i18n';
import {
	TextControl,
	PanelBody,
	PanelRow,
	ToggleControl,
	SelectControl,
} from '@wordpress/components';

import {ErrorNotice} from '../ErrorNotice';

// Constants
import {currencies} from '../../const/currencies.js';
import {dueDate} from '../../const/dueDate.js';
import {invoiceType} from '../../const/invoiceType.js';
import {languages} from '../../const/languages.js';
import {paymentTypeOptions} from '../../const/paymentTypeOptions.js';
import {unitMeasures} from '../../const/unitMeasures.js';

import {STORE_NAME} from '../../store/store';

export const GeneralSettingsPanel = () => {
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
			title={__('Solo Api Settings', 'woo-solo-api')}
			initialOpen={false}
		>
			<div className='components-panel__columns'>
				<div className='components-panel__column'>
					<PanelRow>
						<SelectControl
							ref={(ref) => settingsRefs.current.solo_api_measure = ref}
							className={hasErrorClass('solo_api_measure')}
							name='solo_api_measure'
							label={__('Unit measure', 'woo-solo-api')}
							help={__('Select the default measure in your shop (e.g. piece, hour, m³ etc.)', 'woo-solo-api')}
							value={settings.solo_api_measure}
							disabled={isActive}
							onChange={(value) => setSettings({...settings, solo_api_measure: value, settingsRefs})}
							options={unitMeasures}
						/>
						<ErrorNotice errors={errors} type={'solo_api_measure'} />
					</PanelRow>
					<PanelRow>
						<SelectControl
							ref={(ref) => settingsRefs.current.solo_api_languages = ref}
							className={hasErrorClass('solo_api_languages')}
							name='solo_api_languages'
							label={__('Invoice Language', 'woo-solo-api')}
							help={__('Select the language of the invoice/offer', 'woo-solo-api')}
							value={settings.solo_api_languages}
							disabled={isActive}
							onChange={(value) => setSettings({...settings, solo_api_languages: value, settingsRefs})}
							options={languages}
						/>
						<ErrorNotice errors={errors} type={'solo_api_languages'} />
					</PanelRow>
					<PanelRow>
						<SelectControl
							ref={(ref) => settingsRefs.current.solo_api_currency = ref}
							className={hasErrorClass('solo_api_currency')}
							name='solo_api_currency'
							label={__('Currency', 'woo-solo-api')}
							value={settings.solo_api_currency}
							disabled={isActive}
							onChange={(value) => setSettings({...settings, solo_api_currency: value, settingsRefs})}
							options={currencies}
						/>
						<ErrorNotice errors={errors} type={'solo_api_currency'} />
						<h4 className='components-base-control__information'>{
							createInterpolateElement(
								__('You can check the currency rate at <a>Croatian National Bank</a>.', 'woo-solo-api'),
								{
									a: <a
										href='https://www.hnb.hr/statistika/statisticki-podaci/financijski-sektor/sredisnja-banka-hnb/devizni-tecajevi/tecajna-lista-za-klijente-hnb'
										target='_blank' rel='noopener noreferrer'/> // eslint-disable-line
								} // eslint-disable-line
							)
						}</h4>
						<h4 className='components-base-control__information'>
							{__('The currency will be automatically added if the selected currency is different from EUR. Also, a note about conversion rate will be added to the invoice/offer.', 'woo-solo-api')}
						</h4>
						<br />
					</PanelRow>
					<PanelRow className='components-panel__row--single'>
						<h3 className='components-base-control__subtitle'>
							{__('Choose the type of payment for each enabled payment gateway/type', 'woo-solo-api')}
						</h3>
						{Object.keys(paymentGateways).map((type) => {
							const offer = `solo_api_bill_offer-${type}`;
							const fiscal = `solo_api_fiscalization-${type}`;
							const payment = `solo_api_payment_type-${type}`;

							return <div className='components-panel__item' key={type}>
								<h4>{paymentGateways[type]}</h4>
								<SelectControl
									ref={(ref) => settingsRefs.current[offer] = ref}
									className={hasErrorClass(offer)}
									name={offer}
									label={__('Type of payment document', 'woo-solo-api')}
									value={settings[offer]}
									disabled={isActive}
									onChange={(value) => setSettings({...settings, [offer]: value, settingsRefs})}
									options={[
										{value: 'ponuda', label: __('Offer', 'woo-solo-api')},
										{value: 'racun', label: __('Invoice', 'woo-solo-api')},
									]}
								/>
								<ErrorNotice errors={errors} type={offer} />
								<SelectControl
									ref={(ref) => settingsRefs.current[payment] = ref}
									className={hasErrorClass(payment)}
									name={payment}
									label={__('Payment option types', 'woo-solo-api')}
									value={settings[payment]}
									disabled={isActive}
									onChange={(value) => setSettings({...settings, [payment]: value, settingsRefs})}
									options={paymentTypeOptions}
								/>
								<ErrorNotice errors={errors} type={payment} />
								<ToggleControl
									ref={(ref) => settingsRefs.current[fiscal] = ref}
									className={hasErrorClass(fiscal)}
									name={fiscal}
									label={__('Check if you want the invoice to be fiscalized *', 'woo-solo-api')}
									help={settings[fiscal] ? __('Fiscalize', 'woo-solo-api') : __('Don\'t fiscalize', 'woo-solo-api')}
									checked={settings[fiscal]}
									disabled={isActive}
									onChange={(value) => setSettings({...settings, [fiscal]: value, settingsRefs})}
								/>
								<ErrorNotice errors={errors} type={fiscal} />
							</div>;
						})}
						<h4>{__('* Fiscalization certificate must be added in the SOLO options, and it only applies to invoices.', 'woo-solo-api')}</h4>
					</PanelRow>
				</div>
				<div className='components-panel__column'>
					<PanelRow>
						<TextControl
							ref={(ref) => settingsRefs.current.solo_api_service_type = ref}
							className={`components-base-control__input ${hasErrorClass('solo_api_service_type')}`}
							name='solo_api_service_type'
							label={__('Enter the type of the service', 'woo-solo-api')}
							help={__('You can find it in your account settings (Usluge -> Tipovi usluga)', 'woo-solo-api')}
							type='number'
							disabled={isActive}
							value={settings.solo_api_service_type}
							onChange={(value) => setSettings({...settings, solo_api_service_type: value, settingsRefs})}
						/>
						<ErrorNotice errors={errors} type={'solo_api_service_type'} />
					</PanelRow>
					<PanelRow>
						<ToggleControl
							ref={(ref) => settingsRefs.current.solo_api_show_taxes = ref}
							className={hasErrorClass('solo_api_show_taxes')}
							name='solo_api_show_taxes'
							label={__('Show taxes', 'woo-solo-api')}
							help={settings.solo_api_show_taxes ? __('Show tax', 'woo-solo-api') : __('Don\'t show tax', 'woo-solo-api')}
							checked={settings.solo_api_show_taxes}
							disabled={isActive}
							onChange={() => setSettings({...settings, solo_api_show_taxes: ! settings.solo_api_show_taxes, settingsRefs})}
						/>
						<ErrorNotice errors={errors} type={'solo_api_show_taxes'} />
					</PanelRow>
					<PanelRow>
						<SelectControl
							ref={(ref) => settingsRefs.current.solo_api_invoice_type = ref}
							className={hasErrorClass('solo_api_invoice_type')}
							name='solo_api_invoice_type'
							label={__('Type of invoice', 'woo-solo-api')}
							help={__('Only works with invoice, not with offer', 'woo-solo-api')}
							value={settings.solo_api_invoice_type}
							disabled={isActive}
							onChange={(value) => setSettings({...settings, solo_api_invoice_type: value, settingsRefs})}
							options={invoiceType}
						/>
						<ErrorNotice errors={errors} type={'solo_api_invoice_type'} />
					</PanelRow>
					<PanelRow className='components-panel__row--top components-panel__row--single'>
						<SelectControl
							ref={(ref) => settingsRefs.current.solo_api_due_date = ref}
							className={hasErrorClass('solo_api_due_date')}
							name={'solo_api_due_date'}
							label={__('Invoice/Offer due date', 'woo-solo-api')}
							value={settings.solo_api_due_date}
							disabled={isActive}
							onChange={(value) => setSettings({...settings, solo_api_due_date: value, settingsRefs})}
							options={dueDate}
						/>
						<ErrorNotice errors={errors} type={'solo_api_due_date'} />
					</PanelRow>
				</div>
			</div>
		</PanelBody>
	);
};
