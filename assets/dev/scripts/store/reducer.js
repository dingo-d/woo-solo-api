import {actionTypes} from "./types";

/**
 * Set default store state.
 *
 * The settings will hold the settings API keys. The defaults are:
 *   solo_api_payment_type: '',
 *   solo_api_token: '',
 *   solo_api_measure: '1',
 *   solo_api_languages: '1',
 *   solo_api_currency: '1',
 *   solo_api_service_type: '0',
 *   solo_api_show_taxes: false,
 *   solo_api_invoice_type: '1',
 *   solo_api_mail_title: '',
 *   solo_api_message: '',
 *   solo_api_change_mail_from: '',
 *   solo_api_enable_pin: false,
 *   solo_api_enable_iban: false,
 *   solo_api_due_date: '',
 *   solo_api_mail_gateway: 'a:0:{}',
 *   solo_api_send_pdf: false,
 *   solo_api_send_control: '',
 *   solo_api_available_gateways: 'a:0:{}',
 *
 * @type {{
 *   apiResponse: {},
 *   settings: {},
 *   dbOrders: *[]
 *   }}
 */
const DEFAULT_STATE = {
	apiResponse: {},
	dbOrders: [],
	settings: {},
	errors: {},
	isSaving: false,
	isSaved: false,
};

export const reducer = ( state = DEFAULT_STATE, action ) => {
	switch (action.type) {
		case actionTypes.SET_SETTINGS:
			return {
				...state,
				settings: action.settings
			};

		case actionTypes.SET_ERRORS:
			return {
				...state,
				errors: action.errors
			};

		case actionTypes.SET_IS_SAVING:
			return {
				...state,
				isSaving: action.isSaving
			};

		case actionTypes.SET_IS_SAVED:
			return {
				...state,
				isSaved: action.isSaved
			};

		default: {
			return state;
		}
	}
};
