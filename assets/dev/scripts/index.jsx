/* eslint-disable camelcase */

import Serialize from 'php-serialize';

/**
 * WordPress dependencies
 */
const {__} = wp.i18n;

const {
	BaseControl,
	Button,
	ExternalLink,
	PanelBody,
	PanelRow,
	Placeholder,
	Spinner,
	ToggleControl,
	SelectControl,
} = wp.components;

const {
	render,
	Component,
	Fragment
} = wp.element;

class App extends Component {
	constructor() {
		super(...arguments);

		this.state = {
			isLoading: false,
			isSaving: false,
			errors: {},
			solo_api_token: '',
			solo_api_measure: '1',
			solo_api_payment_type: '',
			solo_api_languages: '1',
			solo_api_currency: '',
			solo_api_service_type: 0,
			solo_api_show_taxes: false,
			solo_api_invoice_type: '',
			solo_api_mail_title: '',
			solo_api_message: '',
			solo_api_change_mail_from: '',
			solo_api_enable_pin: false,
			solo_api_enable_iban: false,
			solo_api_due_date: '',
			solo_api_mail_gateway: [],
			solo_api_send_pdf: false,
			solo_api_send_control: '',
			solo_api_available_gateways: '',
		};

		this.settings = new wp.api.models.Settings();
	}

	async componentDidMount() {
		wp.api.loadPromise.done(() => {
			if (!this.state.isLoading) {
				this.settings.fetch().then(res => {
					this.setState({
						solo_api_token: res.solo_api_token,
						solo_api_measure: res.solo_api_measure,
						solo_api_payment_type: res.solo_api_payment_type,
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
						solo_api_mail_gateway: res.solo_api_mail_gateway,
						solo_api_send_pdf: res.solo_api_send_pdf,
						solo_api_send_control: res.solo_api_send_control,
						solo_api_available_gateways: res.solo_api_available_gateways,
						isLoading: true,
					});
				});
			}
		});
	}

	updateOptions(event) {
		this.setState({
			isSaving: true,
			errors: {}
		});

		const options = Object.keys(this.state)
			.filter(key => key !== 'isLoading' && key !== 'isSaving' && key !== 'errors')
			.reduce((obj, key) => {
				if (!this.objectHasEmptyProperties(this.state[key])) {
					obj[key] = this.state[key];
				}

				return obj;
			}, {});

		this.settings.save(options, {
			success: (model, res) => {
				Object.keys(options).map((option) => {
					this.setState({
						option: res[option],
						isSaving: false,
					});
				});
			},
			error: (model, res) => {
				const errors = res.responseJSON.data.params;

				this.setState({
					errors: errors,
					isSaving: false,
				});
			}
		});
	}

	objectHasEmptyProperties(object) {
		for (const key in object) {
			if (object.hasOwnProperty(key)) {
				if (object[key] != null && object[key] !== "") {
					return false;
				}
			}
		}
		return true;
	}

	render() {
		if (!this.state.isLoading) {
			return (
				<Placeholder>
					<Spinner/>
				</Placeholder>
			);
		}

		const paymentGateways = Serialize.unserialize(this.state.solo_api_available_gateways);

		return (
			<Fragment>
				<PanelBody
					title={__('Solo API token', 'woo-solo-api')}
					initialOpen={true}
				>
					<PanelRow>
						<BaseControl
							id="solo_api_token"
							label={__('Solo API token', 'woo-solo-api')}
							help={__('Enter your personal token that you obtained from your SOLO account', 'woo-solo-api')}
						>
							<div className="components-form-token-field__input-container">
								<input
									className='components-base-control__input components-form-token-field__input'
									type="text"
									id="solo_api_token"
									name="solo_api_token"
									disabled={this.state.isSaving}
									value={this.state.solo_api_token || ''}
									onChange={event => this.setState({solo_api_token: event.target.value})}
								/>
							</div>
						</BaseControl>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('Solo Api Settings', 'woo-solo-api')}
					initialOpen={false}
				>
					<div className='components-panel__columns'>
						<PanelRow>
							<SelectControl
								label={__('Unit measure', 'woo-solo-api')}
								help={__('Select the default measure in your shop (e.g. piece, hour, m^3 etc.)', 'woo-solo-api')}
								disabled={this.state.isSaving}
								value={this.state.solo_api_measure || '1'}
								onChange={solo_api_measure => this.setState({solo_api_measure})}
								options={[
									{value: '1', label: __('-', 'woo-solo-api')},
									{value: '2', label: __('piece', 'woo-solo-api')},
									{value: '3', label: __('hour', 'woo-solo-api')},
									{value: '4', label: __('year', 'woo-solo-api')},
									{value: '5', label: __('km', 'woo-solo-api')},
									{value: '6', label: __('litre', 'woo-solo-api')},
									{value: '7', label: __('kg', 'woo-solo-api')},
									{value: '8', label: __('kWh', 'woo-solo-api')},
									{value: '9', label: __('m³', 'woo-solo-api')},
									{value: '10', label: __('tonne', 'woo-solo-api')},
									{value: '11', label: __('m²', 'woo-solo-api')},
									{value: '12', label: __('m', 'woo-solo-api')},
									{value: '13', label: __('day', 'woo-solo-api')},
									{value: '14', label: __('month', 'woo-solo-api')},
									{value: '15', label: __('night', 'woo-solo-api')},
									{value: '16', label: __('card', 'woo-solo-api')},
									{value: '17', label: __('account', 'woo-solo-api')},
									{value: '18', label: __('pair', 'woo-solo-api')},
									{value: '19', label: __('ml', 'woo-solo-api')},
									{value: '20', label: __('pax', 'woo-solo-api')},
									{value: '21', label: __('room', 'woo-solo-api')},
									{value: '22', label: __('apartment', 'woo-solo-api')},
									{value: '23', label: __('term', 'woo-solo-api')},
									{value: '24', label: __('set', 'woo-solo-api')},
									{value: '25', label: __('package', 'woo-solo-api')},
									{value: '26', label: __('point', 'woo-solo-api')},
									{value: '27', label: __('service', 'woo-solo-api')},
									{value: '28', label: __('pal', 'woo-solo-api')},
									{value: '29', label: __('kont', 'woo-solo-api')},
									{value: '30', label: __('čl', 'woo-solo-api')},
									{value: '31', label: __('tis', 'woo-solo-api')},
									{value: '32', label: __('sec', 'woo-solo-api')},
									{value: '33', label: __('min', 'woo-solo-api')},
								]}
							/>
						</PanelRow>
						<PanelRow>
							<BaseControl
								id="solo_api_service_type"
								label={__('Enter the type of the service', 'woo-solo-api')}
								help={__('You can find it in your account settings (Usluge -> Tipovi usluga). Has to be unique ID of the service', 'woo-solo-api')}
							>
								<div className={"components-form-token-field__input-container" + (this.state.errors.hasOwnProperty('solo_api_service_type') ? ' has-error' : '')}>
									<input
										className='components-base-control__input components-form-token-field__input'
										type="text"
										id="solo_api_service_type"
										name="solo_api_service_type"
										disabled={this.state.isSaving}
										value={this.state.solo_api_service_type || ''}
										onChange={event => this.setState({solo_api_service_type: event.target.value})}
									/>
								</div>
								<p className='components-base-control__error'>
									{
										this.state.errors.hasOwnProperty('solo_api_service_type') &&
										this.state.errors.solo_api_service_type
									}
								</p>
							</BaseControl>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__('Invoice Language', 'woo-solo-api')}
								help={__('Select the language the invoice should be in', 'woo-solo-api')}
								disabled={this.state.isSaving}
								value={this.state.solo_api_languages || '1'}
								onChange={solo_api_languages => this.setState({solo_api_languages})}
								options={[
									{value: '1', label: __('Croatian', 'woo-solo-api')},
									{value: '2', label: __('English', 'woo-solo-api')},
									{value: '3', label: __('German', 'woo-solo-api')},
									{value: '4', label: __('French', 'woo-solo-api')},
									{value: '5', label: __('Italian', 'woo-solo-api')},
									{value: '6', label: __('Spanish', 'woo-solo-api')},
								]}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__('Show taxes', 'woo-solo-api')}
								help={this.state.solo_api_show_taxes ? __('Show tax', 'woo-solo-api') : __('Don\'t show tax', 'woo-solo-api')}
								checked={this.state.solo_api_show_taxes}
								disabled={this.state.isSaving}
								onChange={(solo_api_show_taxes) => this.setState({solo_api_show_taxes})}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__('Currency', 'woo-solo-api')}
								disabled={this.state.isSaving}
								value={this.state.solo_api_currency || '1'}
								onChange={solo_api_currency => this.setState({solo_api_currency})}
								options={[
									{value: '1', label: 'HRK - kn'},
									{value: '2', label: 'AUD - $'},
									{value: '3', label: 'CAD - $'},
									{value: '4', label: 'CZK - Kč'},
									{value: '5', label: 'DKK - kr'},
									{value: '6', label: 'HUF - Ft'},
									{value: '7', label: 'JPY - ¥'},
									{value: '8', label: 'NOK - kr'},
									{value: '9', label: 'SEK - kr'},
									{value: '10', label: 'CHF - CHF'},
									{value: '11', label: 'GBP - £'},
									{value: '12', label: 'USD - $'},
									{value: '13', label: 'BAM - KM'},
									{value: '14', label: 'EUR - €'},
									{value: '15', label: 'PLN - zł'},
								]}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__('Type of invoice', 'woo-solo-api')}
								help={__('Only works with invoice, not with offer', 'woo-solo-api')}
								disabled={this.state.isSaving}
								value={this.state.solo_api_invoice_type || '1'}
								onChange={solo_api_invoice_type => this.setState({solo_api_invoice_type})}
								options={[
									{value: '1', label: 'R'},
									{value: '2', label: 'R1'},
									{value: '3', label: 'R2'},
									{value: '4', label: 'No label'},
									{value: '5', label: 'In advance'},
								]}
							/>
						</PanelRow>
						<PanelRow className='components-panel__row--single'>
							<h3 className='components-base-control__subtitle'>
								{__('Choose the type of payment for each enabled payment gateway', 'woo-solo-api')}
							</h3>
							{paymentGateways.map((value) => {
								const offer = `solo_api_bill_offer-${value}`;
								const fiscal = `solo_api_fiscalization-${value}`;
								const payment = `solo_api_payment_type-${value}`;

								return <div className='components-panel__item' key={value}>
									<ToggleControl
										label={__('Type of payment document', 'woo-solo-api')}
										help={this.state[offer] ? __('Offer', 'woo-solo-api') : __('Invoice', 'woo-solo-api')}
										checked={this.state[offer]}
										disabled={this.state.isSaving}
										onChange={(solo_api_bill_offer) => this.setState({solo_api_bill_offer})}
									/>
								</div>
							})}
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__('Invoice/Offer due date', 'woo-solo-api')}
								disabled={this.state.isSaving}
								value={this.state.solo_api_due_date || '1'}
								onChange={solo_api_due_date => this.setState({solo_api_due_date})}
								options={[
									{value: '1d', label: __('1 day', 'woo-solo-api')},
									{value: '2d', label: __('2 days', 'woo-solo-api')},
									{value: '3d', label: __('3 days', 'woo-solo-api')},
									{value: '4d', label: __('4 days', 'woo-solo-api')},
									{value: '5d', label: __('5 days', 'woo-solo-api')},
									{value: '6d', label: __('6 days', 'woo-solo-api')},
									{value: '1', label: __('1 week', 'woo-solo-api')},
									{value: '2', label: __('2 weeks', 'woo-solo-api')},
									{value: '3', label: __('3 weeks', 'woo-solo-api')},
								]}
							/>
						</PanelRow>
					</div>
				</PanelBody>
				<PanelBody
					title={__('Additional Settings', 'woo-solo-api')}
					initialOpen={false}
				>
					<PanelRow>

					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('Email Settings', 'woo-solo-api')}
					initialOpen={false}
				>
					<PanelRow>
						{}
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('Solo Api Test', 'woo-solo-api')}
					initialOpen={false}
				>
					<PanelRow>

					</PanelRow>
				</PanelBody>
				<div className="options-wrapper__item">
					<Button
						isPrimary
						isLarge
						disabled={this.state.isSaving}
						onClick={(event) => this.updateOptions(event)}
					>
						{__('Save settings', 'woo-solo-api')}
					</Button>
				</div>
			</Fragment>
		);
	}
}


render(
	<App/>,
	document.getElementById('solo-api-options-page')
);
