/* eslint-disable camelcase */
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
	ToggleControl
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
			solo_api_token: '',
			solo_api_measure: '',
			solo_api_payment_type: '',
			solo_api_languages: '',
			solo_api_currency: '',
			solo_api_service_type: '',
			solo_api_show_taxes: '',
			solo_api_invoice_type: '',
			solo_api_mail_title: '',
			solo_api_message: '',
			solo_api_change_mail_from: '',
			solo_api_enable_pin: '',
			solo_api_enable_iban: '',
			solo_api_due_date: '',
			solo_api_mail_gateway: '',
			solo_api_send_pdf: '',
			solo_api_send_control: '',
			solo_api_available_gateways: '',
		};

		this.settings = new wp.api.models.Settings();
	}

	async componentDidMount() {
		debugger;
		wp.api.loadPromise.done(() => {
			if (this.state.isLoading) {
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
		event.preventDefault();
		this.setState({isSaving: true});

		const options = Object.keys(this.state)
			.filter(key => key !== 'isLoading' && key !== 'isSaving')
			.reduce((obj, key) => {
				obj[key] = this.state[key];
				return obj;
			}, {});

		this.settings.set(options).save().then(res => {
			Object.keys(options).map((option) => {
				this.setState({
					[option]: res[option],
					isSaving: false,
				});
			});
		});
	}

	render() {
		if (!this.state.isLoading) {
			return (
				<Placeholder>
					<Spinner/>
				</Placeholder>
			);
		}

		return (
			<Fragment>
				<PanelBody
					title={__('Solo API token')}
					initialOpen={true}
				>
					<PanelRow>
						<BaseControl
							id="apiToken"
							label={__('Solo API token')}
							help={__('Enter your personal token that you obtained from your SOLO account.')}
						>
							<input
								className='components-base-control__input'
								type="text"
								id="apiToken"
								name="apiToken"
								disabled={this.state.isSaving}
								value={this.state.solo_api_token || ''}
								onChange={event => this.setState({solo_api_token: event.target.value})}
							/>
						</BaseControl>
					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('Solo Api Settings')}
					initialOpen={false}
				>
					<PanelRow>

					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('Additional Settings')}
					initialOpen={false}
				>
					<PanelRow>

					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('Email Settings')}
					initialOpen={false}
				>
					<PanelRow>

					</PanelRow>
				</PanelBody>
				<PanelBody
					title={__('Solo Api Test')}
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
						{__('Save settings')}
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
