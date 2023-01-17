import {actionTypes} from "./types";
import {unserialize} from 'php-serialize';

const {apiFetch} = wp;
const {models} = wp.api;

const {
	registerStore,
	select,
} = wp.data;

export const STORE_NAME = 'woo-solo-api/store';

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
 *   isLoading: boolean,
 *   settings: {},
 *   hasErrors: boolean,
 *   isSaved: boolean,
 *   apiResponse: string,
 *   isApiRequestOver: boolean,
 *   isSaving: boolean,
 *   errors: {},
 *   dbOrders: *[]
 *   }}
 */
const DEFAULT_STATE = {
	// State changes.
	isLoading: true,
	isApiRequestOver: true,
	isSaving: false,
	isSaved: false,
	hasErrors: false,
	// Data.
	errors: {},
	apiResponse: '',
	dbOrders: [],
	// Settings.
	settings: {},
};

const actions = {
	setIsLoading(value) {
		return {
			type: actionTypes.SET_IS_LOADING,
			value
		}
	},
	setIsApiRequestOver() {
		return {
			type: actionTypes.SET_IS_API_REQUEST_OVER,
		}
	},
	unsetIsApiRequestOver() {
		return {
			type: actionTypes.UNSET_IS_API_REQUEST_OVER,
		}
	},
	setIsSaving() {
		return {
			type: actionTypes.SET_IS_SAVING,
		}
	},
	unsetIsSaving() {
		return {
			type: actionTypes.UNSET_IS_SAVING,
		}
	},
	setIsSaved() {
		return {
			type: actionTypes.SET_IS_SAVED,
		}
	},
	unsetIsSaved() {
		return {
			type: actionTypes.UNSET_IS_SAVED,
		}
	},
	setHasErrors() {
		return {
			type: actionTypes.SET_HAS_ERRORS,
		}
	},
	unsetHasErrors() {
		return {
			type: actionTypes.UNSET_HAS_ERRORS,
		}
	},
	setErrors(errors) {
		return {
			type: actionTypes.SET_ERRORS,
			errors
		}
	},
	setApiResponse(response) {
		return {
			type: actionTypes.SET_API_RESPONSE,
			response
		}
	},
	setDbOrders(dbOrders) {
		return {
			type: actionTypes.SET_DB_ORDERS,
			dbOrders
		}
	},
	fetchDbOrders(path) {
		return {
			type: actionTypes.FETCH_DB_ORDERS,
			path
		}
	},
	setSettings(settings) {
		return {
			type: actionTypes.SET_SETTINGS,
			settings
		}
	},
	fetchSettings() {
		return {
			type: actionTypes.FETCH_SETTINGS
		}
	},
};

const selectors = {
	getIsLoading(state) {
		return state.isLoading;
	},
	getIsApiRequestOver(state) {
		return state.isApiRequestOver;
	},
	getIsSaving(state) {
		return state.isSaving;
	},
	getIsSaved(state) {
		return state.isSaved;
	},
	getHasErrors(state) {
		return state.hasErrors;
	},
	getErrors(state) {
		return state.errors;
	},
	getError(state, errorName) {
		return state.errors.find((error) => error.errorName === errorName);
	},
	getApiResponses(state) {
		return state.responses;
	},
	getApiResponse(state, responseId) {
		return state.responses.find((response) => response.responseId === responseId);
	},
	getDbOrders(state) {
		return state.dbOrders;
	},
	getSettings(state) {
		return state.settings;
	},
	getSetting(state, settingsId) {
		return state.settings.find((setting) => setting.settingsId === settingsId);
	},
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch (action.type) {
		case 'SET_IS_LOADING': {
			return {
				...state,
				isLoading: action.isLoading,
			};
		}
		case 'SET_IS_API_REQUEST_OVER': {
			return {
				...state,
				isApiRequestOver: true,
			};
		}
		case 'UNSET_IS_API_REQUEST_OVER': {
			return {
				...state,
				isApiRequestOver: false,
			};
		}
		case 'SET_IS_SAVING': {
			return {
				...state,
				isSaving: true,
			};
		}
		case 'UNSET_IS_SAVING': {
			return {
				...state,
				isSaving: false,
			};
		}
		case 'SET_IS_SAVED': {
			return {
				...state,
				isSaved: true,
			};
		}
		case 'UNSET_IS_SAVED': {
			return {
				...state,
				isSaved: false,
			};
		}
		case 'SET_HAS_ERRORS': {
			return {
				...state,
				hasErrors: true,
			};
		}
		case 'UNSET_HAS_ERRORS': {
			return {
				...state,
				hasErrors: false,
			};
		}
		case 'SET_ERRORS': {
			return {
				...state,
				errors: action.errors,
			};
		}
		case 'SET_API_RESPONSE': {
			state.apiResponse = action.apiResponse;
			return state;
		}
		case 'SET_DB_ORDERS': {
			return {
				...state,
				dbOrders: action.dbOrders,
			};
		}
		case 'SET_SETTINGS': {
			return {
				...state,
				settings: action.settings,
			};
		}

		default: {
			return state;
		}
	}
};

const controls = {
	FETCH_DB_ORDERS(action) {
		return apiFetch({path: action.path});
	},
	FETCH_SETTINGS() {
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
	}
};

const resolvers = {
	*getDbOrders() {
		const path = '/woo-solo-api/v1/solo-order-details';
		const dbData = yield actions.fetchDbOrders(path);

		return actions.setDbOrders(dbData);
	},
	*getSettings() {
		const settings = yield actions.fetchSettings();

		return actions.setSettings(settings);
	}
};

// Register the store.
export const setStore = () => {
	if (typeof window?.['wooSoloApi'] === 'undefined') {
		window['wooSoloApi'] = {};
	}

	registerStore(
		STORE_NAME,
		{
			selectors,
			actions,
			resolvers,
			reducer,
			controls,
		}
	);
};

// Set global window data for easier debugging.
export const setStoreGlobalWindow = () => {
	if (typeof window?.['wooSoloApi']?.['store'] === 'undefined') {
		window['wooSoloApi']['store'] = {};
	}

	window['wooSoloApi']['store']['name'] = STORE_NAME;
};
