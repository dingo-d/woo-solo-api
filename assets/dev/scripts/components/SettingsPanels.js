/* eslint-disable camelcase */
const {useState, createInterpolateElement} = wp.element;
const {__} = wp.i18n;
const {apiFetch} = wp;

const {
	Button,
	TextareaControl,
	TextControl,
	CheckboxControl,
	PanelBody,
	PanelRow,
	Spinner,
	ToggleControl,
	SelectControl,
} = wp.components;



import {ErrorNotice} from './ErrorNotice';

// Constants
import {currencies} from './../const/currencies.jsx'
import {dueDate} from './../const/dueDate.jsx';
import {invoiceType} from './../const/invoiceType.jsx';
import {languages} from './../const/languages.jsx';
import {paymentTypeOptions} from './../const/paymentTypeOptions.jsx';
import {unitMeasures} from './../const/unitMeasures.jsx';

export const SettingsPanels = ({isSaving, settings, initialSettings, errors, onUpdate}) => {
	const [apiResponse, setApiResponse] = useState('');
	const [isRequestPending, setIsRequestPending] = useState(false);

	if (Object.keys(settings).length === 0) {
		settings = initialSettings;
	}

	const callApi = async () => {
		setApiResponse('');
		setIsRequestPending(!isRequestPending);

		try {
			const response = await apiFetch({path: '/woo-solo-api/v1/solo-account-details'});
			setApiResponse(response);
		} catch (error) {
			// Populate errors state.
			setIsRequestPending(isRequestPending => !isRequestPending)
		} finally {
			setIsRequestPending(isRequestPending => !isRequestPending)
		}
	};

	const hasErrorClass = (type) => {
		return errors?.hasOwnProperty(type) ? 'has-error' : '';
	}

	const paymentGateways = settings.solo_api_available_gateways;

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
						onChange={value => onUpdate({...settings, solo_api_token: value})}
					/>

					<ErrorNotice errors={errors} type={'solo_api_token'} />
				</PanelRow>
			</PanelBody>
			<PanelBody
				title={__('Solo Api Settings', 'woo-solo-api')}
				initialOpen={false}
			>
				<div className='components-panel__columns'>
					<div className="components-panel__column">
						<PanelRow>
							<SelectControl
								className={hasErrorClass('solo_api_measure')}
								name='solo_api_measure'
								label={__('Unit measure', 'woo-solo-api')}
								help={__('Select the default measure in your shop (e.g. piece, hour, m³ etc.)', 'woo-solo-api')}
								value={settings.solo_api_measure}
								disabled={isSaving}
								onChange={value => onUpdate({...settings, solo_api_measure: value})}
								options={unitMeasures}
							/>

							<ErrorNotice errors={errors} type={'solo_api_measure'} />
						</PanelRow>
						<PanelRow>
							<SelectControl
								className={hasErrorClass('solo_api_languages')}
								name='solo_api_languages'
								label={__('Invoice Language', 'woo-solo-api')}
								help={__('Select the language of the invoice/offer', 'woo-solo-api')}
								value={settings.solo_api_languages}
								disabled={isSaving}
								onChange={value => onUpdate({...settings, solo_api_languages: value})}
								options={languages}
							/>

							<ErrorNotice errors={errors} type={'solo_api_languages'} />
						</PanelRow>
						<PanelRow>
							<SelectControl
								className={hasErrorClass('solo_api_currency')}
								name='solo_api_currency'
								label={__('Currency', 'woo-solo-api')}
								value={settings.solo_api_currency}
								disabled={isSaving}
								onChange={value => onUpdate({...settings, solo_api_currency: value})}
								options={currencies}
							/>

							<ErrorNotice errors={errors} type={'solo_api_currency'} />
							<h4 className="components-base-control__information">{
								createInterpolateElement(
									__('You can check the currency rate at <a>Croatian National Bank</a>.', 'woo-solo-api'),
									{
										a: <a
											href='https://www.hnb.hr/statistika/statisticki-podaci/financijski-sektor/sredisnja-banka-hnb/devizni-tecajevi/tecajna-lista-za-klijente-hnb'
											target='_blank' rel='noopener noreferrer'/>
									}
								)
							}</h4>
							<h4 className="components-base-control__information">
								{__('The currency will be automatically added if the selected currency is different from EUR. Also, a note about conversion rate will be added to the invoice/offer.', 'woo-solo-api')}
							</h4>
							<br />
						</PanelRow>
						<PanelRow className='components-panel__row--single'>
							<h3 className='components-base-control__subtitle'>
								{__('Choose the type of payment for each enabled payment gateway', 'woo-solo-api')}
							</h3>
							{Object.keys(paymentGateways).map((type) => {
								const offer = `solo_api_bill_offer-${type}`;
								const fiscal = `solo_api_fiscalization-${type}`;
								const payment = `solo_api_payment_type-${type}`;

								return <div className='components-panel__item' key={type}>
									<h4>{paymentGateways[type]}</h4>
									<SelectControl
										className={hasErrorClass(offer)}
										name={offer}
										label={__('Type of payment document', 'woo-solo-api')}
										value={settings[offer]}
										disabled={isSaving}
										onChange={value => onUpdate({...settings, [offer]: value})}
										options={[
											{value: 'ponuda', label: __('Offer', 'woo-solo-api')},
											{value: 'racun', label: __('Invoice', 'woo-solo-api')},
										]}
									/>

									<ErrorNotice errors={errors} type={offer} />
									<SelectControl
										className={hasErrorClass(payment)}
										name={payment}
										label={__('Payment option types', 'woo-solo-api')}
										value={settings[payment]}
										disabled={isSaving}
										onChange={value => onUpdate({...settings, [payment]: value})}
										options={paymentTypeOptions}
									/>

									<ErrorNotice errors={errors} type={payment} />
									<ToggleControl
										className={hasErrorClass(fiscal)}
										name={fiscal}
										label={__('Check if you want the invoice to be fiscalized *', 'woo-solo-api')}
										help={settings[fiscal] ? __('Fiscalize', 'woo-solo-api') : __('Don\'t fiscalize', 'woo-solo-api')}
										checked={settings[fiscal]}
										disabled={isSaving}
										onChange={value => onUpdate({...settings, [fiscal]: value})}
									/>

									<ErrorNotice errors={errors} type={fiscal} />
								</div>
							})}
							<h4>{__('* Fiscalization certificate must be added in the SOLO options, and it only applies to invoices.', 'woo-solo-api')}</h4>
						</PanelRow>
					</div>
					<div className="components-panel__column">
						<PanelRow>
							<TextControl
								className={`components-base-control__input ${hasErrorClass('solo_api_service_type')}`}
								name='solo_api_service_type'
								label={__('Enter the type of the service', 'woo-solo-api')}
								help={__('You can find it in your account settings (Usluge -> Tipovi usluga)', 'woo-solo-api')}
								type='text'
								disabled={isSaving}
								value={settings.solo_api_service_type}
								onChange={value => onUpdate({...settings, solo_api_service_type: value})}
							/>

							<ErrorNotice errors={errors} type={'solo_api_service_type'} />
						</PanelRow>
						<PanelRow>
							<ToggleControl
								className={hasErrorClass('solo_api_show_taxes')}
								name='solo_api_show_taxes'
								label={__('Show taxes', 'woo-solo-api')}
								help={settings.solo_api_show_taxes ? __('Show tax', 'woo-solo-api') : __('Don\'t show tax', 'woo-solo-api')}
								checked={settings.solo_api_show_taxes}
								disabled={isSaving}
								onChange={() => onUpdate({...settings, solo_api_show_taxes: !settings.solo_api_show_taxes})}
							/>

							<ErrorNotice errors={errors} type={'solo_api_show_taxes'} />
						</PanelRow>
						<PanelRow>
							<SelectControl
								className={hasErrorClass('solo_api_invoice_type')}
								name='solo_api_invoice_type'
								label={__('Type of invoice', 'woo-solo-api')}
								help={__('Only works with invoice, not with offer', 'woo-solo-api')}
								value={settings.solo_api_invoice_type}
								disabled={isSaving}
								onChange={value => onUpdate({...settings, solo_api_invoice_type: value})}
								options={invoiceType}
							/>

							<ErrorNotice errors={errors} type={'solo_api_invoice_type'} />
						</PanelRow>
						<PanelRow className='components-panel__row--top components-panel__row--single'>
							<SelectControl
								className={hasErrorClass('solo_api_due_date')}
								name={'solo_api_due_date'}
								label={__('Invoice/Offer due date', 'woo-solo-api')}
								value={settings.solo_api_due_date}
								disabled={isSaving}
								onChange={value => onUpdate({...settings, solo_api_due_date: value})}
								options={dueDate}
							/>

							<ErrorNotice errors={errors} type={'solo_api_due_date'} />
						</PanelRow>
					</div>
				</div>
			</PanelBody>
			<PanelBody
				title={__('Additional Settings', 'woo-solo-api')}
				initialOpen={false}
			>
				<div className='components-panel__columns'>
					<div className="components-panel__column">
						<PanelRow className='components-panel__row--single'>
							<h4>{__('WooCommerce checkout settings', 'woo-solo-api')}</h4>
							<ToggleControl
								className={hasErrorClass('solo_api_enable_pin')}
								name='solo_api_enable_pin'
								label={__('Enable the PIN field on the billing and shipping from in the checkout', 'woo-solo-api')}
								help={settings.solo_api_enable_pin ? __('Enable', 'woo-solo-api') : __('Disable', 'woo-solo-api')}
								checked={settings.solo_api_enable_pin}
								disabled={settings.isSaving}
								onChange={() => onUpdate({...settings, solo_api_enable_pin: !settings.solo_api_enable_pin})}
							/>

							<ErrorNotice errors={errors} type={'solo_api_enable_pin'} />
							<ToggleControl
								className={hasErrorClass('solo_api_enable_iban')}
								name='solo_api_enable_iban'
								label={__('Enable the IBAN field on the billing and shipping from in the checkout', 'woo-solo-api')}
								help={settings.solo_api_enable_iban ? __('Enable', 'woo-solo-api') : __('Disable', 'woo-solo-api')}
								checked={settings.solo_api_enable_iban}
								disabled={settings.isSaving}
								onChange={() => onUpdate({...settings, solo_api_enable_iban: !settings.solo_api_enable_iban})}
							/>

							<ErrorNotice errors={errors} type={'solo_api_enable_iban'} />
						</PanelRow>
					</div>
					<div className="components-panel__column">
						<PanelRow className='components-panel__row--single'>
							<h4>{__('PDF settings', 'woo-solo-api')}</h4>
							<ToggleControl
								className={hasErrorClass('solo_api_send_pdf')}
								name='solo_api_send_pdf'
								label={__('Send the email to the client with the PDF of the order or the invoice', 'woo-solo-api')}
								help={settings.solo_api_send_pdf ? __('Send email', 'woo-solo-api') : __('Don\'t send email', 'woo-solo-api')}
								checked={settings.solo_api_send_pdf}
								disabled={settings.isSaving}
								onChange={() => onUpdate({...settings, solo_api_send_pdf: !settings.solo_api_send_pdf})}
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
												className={`components-base-control__checkboxes ${hasErrorClass('solo_api_mail_gateway')}`}
												name={type}
												label={paymentGateways[type]}
												key={type}
												value={type}
												checked={settings.solo_api_mail_gateway.includes(type)}
												onChange={(check) => onUpdate({
													...settings,
													solo_api_mail_gateway: check ?
														[...settings.solo_api_mail_gateway, type] :
														settings.solo_api_mail_gateway.filter(el => el !== type)})}
											/>
										}, this)
									}

									<ErrorNotice errors={errors} type={'solo_api_mail_gateway'} />
								</PanelRow>
								<PanelRow className='components-panel__row--single'>
									<h4>{__('PDF send control', 'woo-solo-api')}</h4>
									<p className='components-base-control__notice'>{__('Decide when to send the PDF of the order or invoice.' +
										'On customer checkout, or when you approve the order in the WooCommerce admin.' +
										'This will determine when the call to the SOLO API will be made', 'woo-solo-api')}</p>
									<SelectControl
										className={hasErrorClass('solo_api_send_control')}
										name='solo_api_send_control'
										label={__('Send on:', 'woo-solo-api')}
										disabled={settings.isSaving}
										value={settings.solo_api_send_control}
										onChange={(value) => onUpdate({...settings, solo_api_send_control: value})}
										options={[
											{value: 'checkout', label: __('Checkout', 'woo-solo-api')},
											{
												value: 'status_change',
												label: __('Status change to \'completed\'', 'woo-solo-api')
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
			<PanelBody
				title={__('Email Settings', 'woo-solo-api')}
				initialOpen={false}
			>
				<PanelRow>
					<TextControl
						className={`components-base-control__input ${hasErrorClass('solo_api_mail_title')}`}
						name='solo_api_mail_title'
						label={__('Set the title of the mail that will be send with the PDF invoice', 'woo-solo-api')}
						type='text'
						disabled={settings.isSaving}
						value={settings.solo_api_mail_title}
						onChange={value => onUpdate({...settings, solo_api_mail_title: value})}
					/>

					<ErrorNotice errors={errors} type={'solo_api_mail_title'} />
				</PanelRow>
				<PanelRow>
					<TextareaControl
						className={`components-base-control__textarea ${hasErrorClass('solo_api_message')}`}
						name='solo_api_message'
						label={__('Type the message that will appear on the mail with the invoice PDF attached', 'woo-solo-api')}
						rows='10'
						disabled={settings.isSaving}
						value={settings.solo_api_message}
						onChange={value => onUpdate({...settings, solo_api_message: value})}
					/>

					<ErrorNotice errors={errors} type={'solo_api_message'} />
				</PanelRow>
				<PanelRow>
					<TextControl
						className={`components-base-control__input ${hasErrorClass('solo_api_change_mail_from')}`}
						name='solo_api_change_mail_from'
						label={__('Change the \'from\' name that shows when WordPress sends the mail', 'woo-solo-api')}
						help={
							createInterpolateElement(
								__('<str>CAUTION</str>: This change is global, every mail send from your WordPress will have this \'from\' name', 'woo-solo-api'),
								{
									str: <strong/>
								}
							)
						}
						type='text'
						disabled={settings.isSaving}
						value={settings.solo_api_change_mail_from}
						onChange={value => onUpdate({...settings, solo_api_change_mail_from: value})}
					/>

					<ErrorNotice errors={errors} type={'solo_api_change_mail_from'} />
				</PanelRow>
			</PanelBody>
			<PanelBody
				title={__('Solo Api Test', 'woo-solo-api')}
				initialOpen={false}
			>
				<PanelRow className='components-panel__row--single'>
					<h4>{__('This serves for testing purposes only', 'woo-solo-api')}</h4>
					<p className='components-base-control__notice'>
						{__('Pressing the button will make a request to your Solo API account, and will list all the invoices or offers you have', 'woo-solo-api')}
					</p>
					<pre className='components-base-control__code'>
						<code>
							{isRequestPending &&
								<Spinner />
							}
							{apiResponse !== '' &&
								apiResponse
							}
						</code>
					</pre>
					<Button
						isPrimary
						isLarge
						disabled={settings.isLoading}
						onClick={callApi}
					>
						{__('Make a request', 'woo-solo-api')}
					</Button>
					<p className='components-base-control__notice'>
						{__('Pressing the button will clear the Croatian Central Bank (HNB) API transient.', 'woo-solo-api')}
					</p>
					<Button
						isPrimary
						isLarge
						disabled={settings.isLoading}
						// onClick={this.deleteTransient}
					>
						{__('Delete exchange rate transient', 'woo-solo-api')}
					</Button>
				</PanelRow>
			</PanelBody>
		</>
	)
}
