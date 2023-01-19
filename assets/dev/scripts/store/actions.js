import {actionTypes} from "./types";

export const actions = {
	setSettings(settings) {
		return {
			type: actionTypes.SET_SETTINGS,
			settings
		}
	},

	setDbOrders(dbOrders) {
		return {
			type: actionTypes.SET_DB_ORDERS,
			dbOrders
		}
	},

	getSettings() {
		return {
			type: actionTypes.GET_SETTINGS,
		}
	},

	fetchFromApi(path) {
		return {
			type: actionTypes.FETCH_FROM_API,
			path,
		}
	}
};
