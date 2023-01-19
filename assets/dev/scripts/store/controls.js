import {unserialize} from 'php-serialize';

const {apiFetch} = wp;
const {models} = wp.api;

export const controls = {
	FETCH_FROM_API(action) {
		return apiFetch({path: action.path});
	},
	GET_SETTINGS() {
		const settingsModel = new models.Settings();

		return settingsModel.fetch().then(res => {
			let availableGateways;
			let settings = {};

			if (res.solo_api_available_gateways === null) {
				availableGateways = [];
			} else {
				availableGateways = res.solo_api_available_gateways.length ?
					unserialize(res.solo_api_available_gateways) :
					[];
			}

			Object.keys(availableGateways).forEach(gatewayType => {
				settings[`solo_api_bill_offer-${gatewayType}`] = res[`solo_api_bill_offer-${gatewayType}`];
				settings[`solo_api_fiscalization-${gatewayType}`] = res[`solo_api_fiscalization-${gatewayType}`];
				settings[`solo_api_payment_type-${gatewayType}`] = res[`solo_api_payment_type-${gatewayType}`];
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

			return {...settings, ...{
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
			}};
		});
	},
};
