const {
	createReduxStore,
	register,
	select,
} = wp.data;

export const STORE_NAME = 'woo-solo-api-store';

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
	setIsLoading() {
		return {
			type: 'SET_IS_LOADING',
		}
	},
	unsetIsLoading() {
		return {
			type: 'UNSET_IS_LOADING',
		}
	},
	setIsApiRequestOver() {
		return {
			type: 'SET_IS_API_REQUEST_OVER',
		}
	},
	unsetIsApiRequestOver() {
		return {
			type: 'UNSET_IS_API_REQUEST_OVER',
		}
	},
	setIsSaving() {
		return {
			type: 'SET_IS_SAVING',
		}
	},
	unsetIsSaving() {
		return {
			type: 'UNSET_IS_SAVING',
		}
	},
	setIsSaved() {
		return {
			type: 'SET_IS_SAVED',
		}
	},
	unsetIsSaved() {
		return {
			type: 'UNSET_IS_SAVED',
		}
	},
	setHasErrors() {
		return {
			type: 'SET_HAS_ERRORS',
		}
	},
	unsetHasErrors() {
		return {
			type: 'UNSET_HAS_ERRORS',
		}
	},
	setErrors(errors) {
		return {
			type: 'SET_ERRORS',
			errors
		}
	},
	setApiResponse(response) {
		return {
			type: 'SET_API_RESPONSE',
			response
		}
	},
	setDbOrders(dbOrders) {
		return {
			type: 'SET_DB_ORDERS',
			dbOrders
		}
	},
	setDbOrderByIndex(dbOrders, index) {
		return {
			type: 'SET_DB_ORDER_BY_INDEX',
			dbOrders,
			index,
		};
	},
	unsetDbOrderByIndex(index) {
		return {
			type: 'UNSET_DB_ORDER_BY_INDEX',
			index,
		};
	},
	setSettings(settings) {
		return {
			type: 'SET_SETTINGS',
			settings
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
	getDbOrder(state, orderId) {
		return state.dbOrders.find((dbOrder) => dbOrder.orderId === orderId);
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
				isLoading: true,
			};
		}
		case 'UNSET_IS_LOADING': {
			return {
				...state,
				isLoading: false,
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
			state.dbOrders.push(action.dbOrders);
			return state;
		}
		case 'SET_DB_ORDER_BY_INDEX': {
			if (JSON.stringify(state.dbOrders[action.index]) !== JSON.stringify(action.dbOrders)) {
				state.dbOrders[action.index] = action.dbOrders;
				return state;
			}

			return state;
		}
		case 'UNSET_DB_ORDER_BY_INDEX': {
			let internalDbOrders = {
				...state,
			};

			internalDbOrders.dbOrders.splice(action.index, 1);

			return internalDbOrders;
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

// Register the store.
export const setStore = () => {
	if (typeof window?.['wooSoloApi'] === 'undefined') {
		window['wooSoloApi'] = {};
	}

	const WooSoloApiStore = createReduxStore(STORE_NAME,
		{
			selectors,
			actions,
			reducer,
		}
	);

	register(WooSoloApiStore);
};

// Set global window data for easier debugging.
export const setStoreGlobalWindow = () => {
	if (typeof window?.['wooSoloApi']?.['store'] === 'undefined') {
		window['wooSoloApi']['store'] = {};
	}

	window['wooSoloApi']['store'][select(STORE_NAME)] = STORE_NAME;
};
