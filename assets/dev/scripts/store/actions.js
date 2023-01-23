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
	setErrors(errors) {
		return {
			type: actionTypes.SET_ERRORS,
			errors
		}
	},
	setIsSaving(isSaving) {
		return {
			type: actionTypes.SET_IS_SAVING,
			isSaving
		}
	},
	setIsSaved(isSaved) {
		return {
			type: actionTypes.SET_IS_SAVED,
			isSaved
		}
	},
	getSettings() {
		return {
			type: actionTypes.GET_SETTINGS,
		}
	},
	getErrors() {
		return {
			type: actionTypes.GET_ERRORS,
		}
	},
	getIsSaving() {
		return {
			type: actionTypes.GET_IS_SAVING,
		}
	},
	getIsSaved() {
		return {
			type: actionTypes.GET_IS_SAVED,
		}
	},
	fetchFromApi(path) {
		return {
			type: actionTypes.FETCH_FROM_API,
			path,
		}
	}
};
