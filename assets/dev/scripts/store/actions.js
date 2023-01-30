import {actionTypes} from './types';

export const actions = {
	setSettings(settings) {
		return {
			type: actionTypes.SET_SETTINGS,
			settings,
		};
	},
	setDbOrders(dbOrders) {
		return {
			type: actionTypes.SET_DB_ORDERS,
			dbOrders,
		};
	},
	setErrors(errors) {
		return {
			type: actionTypes.SET_ERRORS,
			errors,
		};
	},
	setIsActive(isActive) {
		return {
			type: actionTypes.SET_IS_ACTIVE,
			isActive,
		};
	},
	setIsSaved(isSaved) {
		return {
			type: actionTypes.SET_IS_SAVED,
			isSaved,
		};
	},
	getSettings() {
		return {
			type: actionTypes.GET_SETTINGS,
		};
	},
	getDbOrders() {
		return {
			type: actionTypes.GET_DB_ORDERS,
		};
	},
	getErrors() {
		return {
			type: actionTypes.GET_ERRORS,
		};
	},
	getIsActive() {
		return {
			type: actionTypes.GET_IS_ACTIVE,
		};
	},
	getIsSaved() {
		return {
			type: actionTypes.GET_IS_SAVED,
		};
	},
	fetchFromApi(path) {
		return {
			type: actionTypes.FETCH_FROM_API,
			path,
		};
	},
};
