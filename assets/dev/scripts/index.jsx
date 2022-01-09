/* eslint-disable camelcase */

import {serialize, unserialize} from 'php-serialize';
import {bind} from 'decko';

// Constants
import {currencies} from './const/currencies.jsx';
import {dueDate} from './const/dueDate.jsx';
import {invoiceType} from './const/invoiceType.jsx';
import {languages} from './const/languages.jsx';
import {paymentTypeOptions} from './const/paymentTypeOptions.jsx';
import {unitMeasures} from './const/unitMeasures.jsx';

/**
 * WordPress dependencies
 */
const {__} = wp.i18n;
const {apiFetch} = wp;

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

const {
	render,
	Fragment,
	Component,
	createInterpolateElement
} = wp.element;

const defaultState = {
	isLoading: true,
	isApiRequestOver: true,
	isSaving: false,
	isSaved: false,
	hasErrors: false,
	errors: {},
	apiResponse: '',
	dbOrders: [],
	solo_api_payment_type: '',
	solo_api_token: '',
	solo_api_measure: '1',
	solo_api_languages: '1',
	solo_api_currency: '1',
	solo_api_service_type: '0',
	solo_api_show_taxes: false,
	solo_api_invoice_type: '1',
	solo_api_mail_title: '',
	solo_api_message: '',
	solo_api_change_mail_from: '',
	solo_api_enable_pin: false,
	solo_api_enable_iban: false,
	solo_api_due_date: '',
	solo_api_mail_gateway: 'a:0:{}',
	solo_api_send_pdf: false,
	solo_api_send_control: '',
	solo_api_available_gateways: 'a:0:{}',
};

class App extends Component {
	constructor() {
		super(...arguments);

		this.state = defaultState;

		this.settings = {};
	}

	componentDidMount() {
		wp.api.loadPromise.done(() => {
			if (this.state.isLoading) {
				this.settings = new wp.api.models.Settings();

				this.settings.fetch().then(res => {

					let availableGateways;

					if (res.solo_api_available_gateways === null) {
						availableGateways = [];
					} else {
						availableGateways = res.solo_api_available_gateways.length ?
							unserialize(res.solo_api_available_gateways) :
							[];
					}

					Object.keys(availableGateways).forEach(gatewayType => {
						this.setState({
							[`solo_api_bill_offer-${gatewayType}`]: res[`solo_api_bill_offer-${gatewayType}`],
							[`solo_api_fiscalization-${gatewayType}`]: res[`solo_api_fiscalization-${gatewayType}`],
							[`solo_api_payment_type-${gatewayType}`]: res[`solo_api_payment_type-${gatewayType}`],
						});
					});

					let mailGateways;

					if (res.solo_api_mail_gateway === null) {
						mailGateways = [];
					} else {
						mailGateways = res.solo_api_mail_gateway.length ?
							unserialize(res.solo_api_mail_gateway) :
							[];
					}

					const solo_api_payment_type = typeof res.solo_api_payment_type !== 'undefined' ?
						res.solo_api_payment_type :
						'';

					this.setState({
						solo_api_token: res.solo_api_token,
						solo_api_measure: res.solo_api_measure,
						solo_api_payment_type: solo_api_payment_type,
						solo_api_languages: res.solo_api_languages,
						solo_api_currency: res.solo_api_currency,
						solo_api_service_type: res.solo_api_service_type,
						solo_api_show_taxes: res.solo_api_show_taxes,
						solo_api_invoice_type: res.solo_api_invoice_type,
						solo_api_mail_title: res.solo_api_mail_title,
						solo_api_message: res.solo_api_message,
						solo_api_change_mail_from: res.solo_api_change_mail_from,
						solo_api_enable_pin: res.solo_api_enable_pin,
						solo_api_enable_iban: res.solo_api_enable_iban,
						solo_api_due_date: res.solo_api_due_date,
						solo_api_mail_gateway: mailGateways,
						solo_api_send_pdf: res.solo_api_send_pdf,
						solo_api_send_control: res.solo_api_send_control,
						solo_api_available_gateways: availableGateways,
						isLoading: false,
					});
				}).then(
					apiFetch({path: '/woo-solo-api/v1/solo-order-details'}).then(res => {
						this.setState({
							dbOrders: res,
						});
					})
				);

			}
		});
	}

	@bind
	setStateSynchronous(stateUpdate) {
		return new Promise(resolve => {
			this.setState(stateUpdate, () => resolve());
		})
	}

	@bind
	async updateOptions() {
		await this.setStateSynchronous(
			state => ({isSaving: true, errors: {}})
		);

		// Create options object.
		const options = Object.keys(this.state)
			.filter(key => (key !== 'isLoading' &&
				key !== 'isSaving' &&
				key !== 'hasErrors' &&
				key !== 'errors' &&
				key !== 'apiResponse' &&
				key !== 'dbOrders' &&
				key !== 'isApiRequestOver' &&
				key !== 'isSaved'))
			.reduce((obj, key) => {
				if (!this.objectHasEmptyProperties(this.state)) {
					if (key === 'solo_api_available_gateways' || key === 'solo_api_mail_gateway') {
						obj[key] = serialize(this.state[key]);
					} else if (this.state[key] === null || typeof this.state[key] == 'undefined') {
						obj[key] = defaultState[key];
					} else {
						obj[key] = this.state[key];
					}
				}

				return obj;
			}, {});

		this.settings.save(options, {
			success: (model, res) => {
				Object.keys(options).map((option) => {
					this.setState({
						option: res[option],
						isSaving: false,
						isSaved: true,
						hasErrors: false,
					});
				});

				setTimeout(() => {
					this.setState({
						isSaved: false
					});
				}, 3000);
			},
			error: (model, res) => {
				const errors = res.responseJSON.data.params;

				this.setState({
					errors: errors,
					isSaving: false,
					isSaved: true,
					hasErrors: true,
				});

				const target = document.querySelector(`[name=${Object.keys(errors)[0]}]`);

				window.scrollTo({
					top: target.offsetTop - 30,
					left: 0,
					behavior: 'smooth',
				});

				setTimeout(() => {
					this.setState({
						isSaved: false
					});
				}, 3000);
			}
		});
	}

	renderSnackbar() {
		return (
			<Snackbar className={this.state.isSaved ? 'is-visible' : 'is-hidden'}>
				{
					this.state.hasErrors ?
						__('Errors happened during saving', 'woo-solo-api') :
						__('Settings saved', 'woo-solo-api')
				}
			</Snackbar>
		)
	}

	objectHasEmptyProperties(object) {
		for (const key in object) {
			if (object.hasOwnProperty(key)) {
				if (object[key] != null && object[key] !== '' && typeof(object[key]) !== 'undefined') {
					return false;
				}
			}
		}

		return true;
	}

	updateMailGateways(check, type) {
		this.setState((currentState) => {
			return {
				solo_api_mail_gateway: check ? [...currentState.solo_api_mail_gateway, type] :
					currentState.solo_api_mail_gateway.filter(el => el !== type)
			}
		});
	}

	renderError(type) {
		if (this.state.errors.hasOwnProperty(type)) {
			return (
				<p className='components-base-control__error'>
					{__('Validation error: ', 'woo-solo-api') + this.state.errors[type]}
				</p>
			)
		}
	}

	hasErrorClass(type) {
		return this.state.errors.hasOwnProperty(type) ? 'has-error' : '';
	}

	@bind
	callApi() {
		this.setState({
			isApiRequestOver: false,
		});

		if (this.state.solo_api_token === '') {
			return this.setState({apiResponse: __('Empty API token', 'woo-solo-api')});
		}

		apiFetch({path: '/woo-solo-api/v1/solo-account-details'}).then(res => {
			this.setState({
				apiResponse: res,
				isApiRequestOver: true
			});
		});
	}

	renderNotification() {
		return (
			<div className='notice'>
				<p>{__('For more details on the options you can read the official SOLO API documentation here: ', 'woo-solo-api')}
					<ExternalLink
						href='https://solo.com.hr/api-dokumentacija'>https://solo.com.hr/api-dokumentacija</ExternalLink>
				</p>
			</div>
		)
	}

	render() {
		if (this.state.isLoading) {
			return (
				<Fragment>
					{this.renderNotification()}
					<div className='options-wrapper is-loading'>
						<Spinner/>
					</div>
				</Fragment>
			);
		}

		const paymentGateways = this.state.solo_api_available_gateways;

		return (
			<Fragment>
				{this.renderNotification()}
				<div className='options-wrapper'>
					<PanelBody
						title={__('Solo API token', 'woo-solo-api')}
						initialOpen={true}
					>
						<PanelRow>
							<TextControl
								className={`components-base-control__input ${this.hasErrorClass('solo_api_token')}`}
								name='solo_api_token'
								label={__('Solo API token', 'woo-solo-api')}
								help={__('Enter your personal token that you obtained from your SOLO account', 'woo-solo-api')}
								type='text'
								disabled={this.state.isSaving}
								value={this.state.solo_api_token}
								onChange={value => this.setState({solo_api_token: value})}
							/>
							{this.renderError('solo_api_token')}
						</PanelRow>
					</PanelBody>
					<PanelBody
						title={__('Solo Api Settings', 'woo-solo-api')}
						initialOpen={false}
					>
						<div className='components-panel__columns'>
							<PanelRow>
								<SelectControl
									className={this.hasErrorClass('solo_api_measure')}
									name='solo_api_measure'
									label={__('Unit measure', 'woo-solo-api')}
									help={__('Select the default measure in your shop (e.g. piece, hour, m³ etc.)', 'woo-solo-api')}
									value={this.state.solo_api_measure}
									disabled={this.state.isSaving}
									onChange={solo_api_measure => this.setState({solo_api_measure})}
									options={unitMeasures}
								/>
								{this.renderError('solo_api_measure')}
							</PanelRow>
							<PanelRow>
								<TextControl
									className={`components-base-control__input ${this.hasErrorClass('solo_api_service_type')}`}
									name='solo_api_service_type'
									label={__('Enter the type of the service', 'woo-solo-api')}
									help={__('You can find it in your account settings (Usluge -> Tipovi usluga)', 'woo-solo-api')}
									type='text'
									disabled={this.state.isSaving}
									value={this.state.solo_api_service_type}
									onChange={value => this.setState({solo_api_service_type: value})}
								/>
								{this.renderError('solo_api_service_type')}
							</PanelRow>
							<PanelRow>
								<SelectControl
									className={this.hasErrorClass('solo_api_languages')}
									name='solo_api_languages'
									label={__('Invoice Language', 'woo-solo-api')}
									help={__('Select the language of the invoice/offer', 'woo-solo-api')}
									value={this.state.solo_api_languages}
									disabled={this.state.isSaving}
									onChange={solo_api_languages => this.setState({solo_api_languages})}
									options={languages}
								/>
								{this.renderError('solo_api_languages')}
							</PanelRow>
							<PanelRow>
								<ToggleControl
									className={this.hasErrorClass('solo_api_show_taxes')}
									name='solo_api_show_taxes'
									label={__('Show taxes', 'woo-solo-api')}
									help={this.state.solo_api_show_taxes ? __('Show tax', 'woo-solo-api') : __('Don\'t show tax', 'woo-solo-api')}
									checked={this.state.solo_api_show_taxes}
									disabled={this.state.isSaving}
									onChange={() => this.setState((state) => ({solo_api_show_taxes: !state.solo_api_show_taxes}))}
								/>
								{this.renderError('solo_api_show_taxes')}
							</PanelRow>
							<PanelRow>
								<SelectControl
									className={this.hasErrorClass('solo_api_currency')}
									name='solo_api_currency'
									label={__('Currency', 'woo-solo-api')}
									value={this.state.solo_api_currency}
									disabled={this.state.isSaving}
									onChange={solo_api_currency => this.setState({solo_api_currency})}
									options={currencies}
								/>
								{this.renderError('solo_api_currency')}
							</PanelRow>
							<PanelRow>
								<SelectControl
									className={this.hasErrorClass('solo_api_invoice_type')}
									name='solo_api_invoice_type'
									label={__('Type of invoice', 'woo-solo-api')}
									help={__('Only works with invoice, not with offer', 'woo-solo-api')}
									value={this.state.solo_api_invoice_type}
									disabled={this.state.isSaving}
									onChange={solo_api_invoice_type => this.setState({solo_api_invoice_type})}
									options={invoiceType}
								/>
								{this.renderError('solo_api_invoice_type')}
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
											className={this.hasErrorClass(offer)}
											name={offer}
											label={__('Type of payment document', 'woo-solo-api')}
											value={this.state[offer]}
											disabled={this.state.isSaving}
											onChange={offerType => this.setState({[offer]: offerType})}
											options={[
												{value: 'ponuda', label: __('Offer', 'woo-solo-api')},
												{value: 'racun', label: __('Invoice', 'woo-solo-api')},
											]}
										/>
										{this.renderError(offer)}
										<SelectControl
											className={this.hasErrorClass(payment)}
											name={payment}
											label={__('Payment option types', 'woo-solo-api')}
											value={this.state[payment]}
											disabled={this.state.isSaving}
											onChange={paymentType => this.setState({[payment]: paymentType})}
											options={paymentTypeOptions}
										/>
										{this.renderError(payment)}
										<ToggleControl
											className={this.hasErrorClass(fiscal)}
											name={fiscal}
											label={__('Check if you want the invoice to be fiscalized *', 'woo-solo-api')}
											help={this.state[fiscal] ? __('Fiscalize', 'woo-solo-api') : __('Don\'t fiscalize', 'woo-solo-api')}
											checked={this.state[fiscal]}
											disabled={this.state.isSaving}
											onChange={fiscalize => this.setState({[fiscal]: fiscalize})}
										/>
										{this.renderError(fiscal)}
									</div>
								})}
								<h4>{__('* Fiscalization certificate must be added in the SOLO options, and it only applies to invoices.', 'woo-solo-api')}</h4>
							</PanelRow>
							<PanelRow className='components-panel__row--top components-panel__row--single'>
								<SelectControl
									className={this.hasErrorClass('solo_api_due_date')}
									name={'solo_api_due_date'}
									label={__('Invoice/Offer due date', 'woo-solo-api')}
									value={this.state.solo_api_due_date}
									disabled={this.state.isSaving}
									onChange={solo_api_due_date => this.setState({solo_api_due_date})}
									options={dueDate}
								/>
								{this.renderError('solo_api_due_date')}
								<h4 className="components-base-control__information">{
									createInterpolateElement(
										__('You can check the currency rate at <a>Croatian National Bank</a>.', 'woo-solo-api'),
										{
											a: <a
												href='https://www.hnb.hr/temeljne-funkcije/monetarna-politika/tecajna-lista/tecajna-lista'
												target='_blank' rel='noopener noreferrer'/>
										}
									)
								}</h4>
								<h4 className="components-base-control__information">
									{__('The currency will be automatically added if the selected currency is different from HRK. Also, a note about conversion rate will be added to the invoice/offer.', 'woo-solo-api')}
								</h4>
							</PanelRow>
						</div>
					</PanelBody>
					<PanelBody
						title={__('Additional Settings', 'woo-solo-api')}
						initialOpen={false}
					>
						<PanelRow className='components-panel__row--single'>
							<h4>{__('WooCommerce checkout settings', 'woo-solo-api')}</h4>
							<ToggleControl
								className={this.hasErrorClass('solo_api_enable_pin')}
								name='solo_api_enable_pin'
								label={__('Enable the PIN field on the billing and shipping from in the checkout', 'woo-solo-api')}
								help={this.state.solo_api_enable_pin ? __('Enable', 'woo-solo-api') : __('Disable', 'woo-solo-api')}
								checked={this.state.solo_api_enable_pin}
								disabled={this.state.isSaving}
								onChange={() => this.setState((state) => ({solo_api_enable_pin: !state.solo_api_enable_pin}))}
							/>
							{this.renderError('solo_api_enable_pin')}
							<ToggleControl
								className={this.hasErrorClass('solo_api_enable_iban')}
								name='solo_api_enable_iban'
								label={__('Enable the IBAN field on the billing and shipping from in the checkout', 'woo-solo-api')}
								help={this.state.solo_api_enable_iban ? __('Enable', 'woo-solo-api') : __('Disable', 'woo-solo-api')}
								checked={this.state.solo_api_enable_iban}
								disabled={this.state.isSaving}
								onChange={() => this.setState((state) => ({solo_api_enable_iban: !state.solo_api_enable_iban}))}
							/>
							{this.renderError('solo_api_enable_iban')}
						</PanelRow>
						<PanelRow className='components-panel__row--single'>
							<h4>{__('PDF settings', 'woo-solo-api')}</h4>
							<ToggleControl
								className={this.hasErrorClass('solo_api_send_pdf')}
								name='solo_api_send_pdf'
								label={__('Send the email to the client with the PDF of the order or the invoice', 'woo-solo-api')}
								help={this.state.solo_api_send_pdf ? __('Send email', 'woo-solo-api') : __('Don\'t send email', 'woo-solo-api')}
								checked={this.state.solo_api_send_pdf}
								disabled={this.state.isSaving}
								onChange={() => this.setState((state) => ({solo_api_send_pdf: !state.solo_api_send_pdf}))}
							/>
							{this.renderError('solo_api_send_pdf')}
						</PanelRow>
						<PanelRow className='components-panel__row--single'>
							<h4>{__('Send mail to selected payment gateways', 'woo-solo-api')}</h4>
							{
								Object.keys(paymentGateways).map((type) => {
									return <CheckboxControl
										className={`components-base-control__checkboxes ${this.hasErrorClass('solo_api_mail_gateway')}`}
										name={type}
										label={paymentGateways[type]}
										key={type}
										value={type}
										checked={this.state.solo_api_mail_gateway.includes(type)}
										onChange={(check) => this.updateMailGateways(check, type)}
									/>
								}, this)
							}
							{this.renderError('solo_api_mail_gateway')}
						</PanelRow>
						<PanelRow className='components-panel__row--single'>
							<h4>{__('PDF send control', 'woo-solo-api')}</h4>
							<p className='components-base-control__notice'>{__('Decide when to send the PDF of the order or invoice.' +
								'On customer checkout, or when you approve the order in the WooCommerce admin.' +
								'This will determine when the call to the SOLO API will be made', 'woo-solo-api')}</p>
							<SelectControl
								className={this.hasErrorClass('solo_api_send_control')}
								name='solo_api_send_control'
								label={__('Send on:', 'woo-solo-api')}
								disabled={this.state.isSaving}
								value={this.state.solo_api_send_control}
								onChange={offerType => this.setState({solo_api_send_control: offerType})}
								options={[
									{value: 'checkout', label: __('Checkout', 'woo-solo-api')},
									{
										value: 'status_change',
										label: __('Status change to \'completed\'', 'woo-solo-api')
									},
								]}
							/>
							{this.renderError('solo_api_send_control')}
						</PanelRow>
					</PanelBody>
					<PanelBody
						title={__('Email Settings', 'woo-solo-api')}
						initialOpen={false}
					>
						<PanelRow>
							<TextControl
								className={`components-base-control__input ${this.hasErrorClass('solo_api_mail_title')}`}
								name='solo_api_mail_title'
								label={__('Set the title of the mail that will be send with the PDF invoice', 'woo-solo-api')}
								type='text'
								disabled={this.state.isSaving}
								value={this.state.solo_api_mail_title}
								onChange={value => this.setState({solo_api_mail_title: value})}
							/>
							{this.renderError('solo_api_mail_title')}
						</PanelRow>
						<PanelRow>
							<TextareaControl
								className={`components-base-control__textarea ${this.hasErrorClass('solo_api_message')}`}
								name='solo_api_message'
								label={__('Type the message that will appear on the mail with the invoice PDF attached', 'woo-solo-api')}
								rows='10'
								disabled={this.state.isSaving}
								value={this.state.solo_api_message}
								onChange={value => this.setState({solo_api_message: value})}
							/>
							{this.renderError('solo_api_message')}
						</PanelRow>
						<PanelRow>
							<TextControl
								className={`components-base-control__input ${this.hasErrorClass('solo_api_change_mail_from')}`}
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
								disabled={this.state.isSaving}
								value={this.state.solo_api_change_mail_from}
								onChange={value => this.setState({solo_api_change_mail_from: value})}
							/>
							{this.renderError('solo_api_change_mail_from')}
						</PanelRow>
					</PanelBody>
					<PanelBody
						title={__('Solo Api Test', 'woo-solo-api')}
						initialOpen={false}
					>
						<PanelRow className='components-panel__row--single'>
							<h4>{__('This serves for testing purposes only', 'woo-solo-api')}</h4>
							<p className='components-base-control__notice'>
								{__('Pressing the button will make a request to your Solo API account,' +
									'and will list all the invoices you have', 'woo-solo-api')}
							</p>
							<pre className='components-base-control__code'>
								<code>
									{!this.state.isApiRequestOver ? <Spinner/> : ''}
									{this.state.apiResponse}
								</code>
							</pre>
							<Button
								isPrimary
								isLarge
								disabled={this.state.isLoading}
								onClick={this.callApi}
							>
								{__('Make a request', 'woo-solo-api')}
							</Button>
						</PanelRow>
					</PanelBody>
					<div className='options-wrapper__item'>
						<Button
							isPrimary
							isLarge
							disabled={this.state.isSaving}
							onClick={this.updateOptions}
						>
							{__('Save settings', 'woo-solo-api')}
						</Button>
						{this.state.isSaving ? <Spinner/> : ''}
						{this.renderSnackbar()}
					</div>
				</div>
				<div className="options-wrapper options-wrapper--separated">
					<PanelBody
						title={__('Solo API order details', 'woo-solo-api')}
						initialOpen={false}
					>
						<PanelRow>
							<div className="details-table">
								<div className="details-table__element details-table__element--heading">{__('ID', 'woo-solo-api')}</div>
								<div className="details-table__element details-table__element--heading">{__('WooCommerce Order ID', 'woo-solo-api')}</div>
								<div className="details-table__element details-table__element--heading">{__('Solo ID', 'woo-solo-api')}</div>
								<div className="details-table__element details-table__element--heading">{__('Customer Email', 'woo-solo-api')}</div>
								<div className="details-table__element details-table__element--heading">{__('Is sent to Solo API', 'woo-solo-api')}</div>
								<div className="details-table__element details-table__element--heading">{__('Is pdf sent to customer', 'woo-solo-api')}</div>
								<div className="details-table__element details-table__element--heading">{__('API response errors', 'woo-solo-api')}</div>
								<div className="details-table__element details-table__element--heading">{__('Created at', 'woo-solo-api')}</div>
								<div className="details-table__element details-table__element--heading">{__('Updated at', 'woo-solo-api')}</div>
								{this.state.dbOrders.map((el) => {
									return <Fragment>
										<div className="details-table__element">{el.id}</div>
										<div className="details-table__element">
											<ExternalLink href={`/wp-admin/post.php?post=${el.order_id}&action=edit`}>{el.order_id}</ExternalLink>
										</div>
										<div className="details-table__element">{el.solo_id || ''}</div>
										<div className="details-table__element">{el.customer_email}</div>
										<div className="details-table__element">{el.is_sent_to_api === '1' ? '✅' : '❌'}</div>
										<div className="details-table__element">{el.is_sent_to_user === '1' ? '✅' : '❌'}</div>
										<div className="details-table__element">{el.error_message}</div>
										<div className="details-table__element">{el.created_at}</div>
										<div className="details-table__element">{el.updated_at}</div>
									</Fragment>
								})}
							</div>
						</PanelRow>
					</PanelBody>
				</div>
			</Fragment>
		);
	}
}

render(
	<App/>,
	document.getElementById('solo-api-options-page')
);
