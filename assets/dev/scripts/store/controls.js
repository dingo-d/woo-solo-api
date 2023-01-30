/* eslint-disable camelcase, no-unused-vars */
/* global wp */
/* eslint operator-linebreak: ["error", "after"] */

import apiFetch from '@wordpress/api-fetch';
import {useSelect} from '@wordpress/data';

import {STORE_NAME} from './store';

export const controls = {
	FETCH_FROM_API(action) {
		return apiFetch({path: action.path});
	},
	GET_SETTINGS() {
		return useSelect((select) => select(STORE_NAME).getSettings());
	},
	GET_ERRORS() {
		const errors = useSelect((select) => select(STORE_NAME).getErrors());
		return typeof errors !== 'undefined' ?
			errors :
			{};
	},
	GET_DB_ORDERS() {
		return useSelect((select) => select(STORE_NAME).getDbOrders());
	},
	GET_IS_ACTIVE() {
		return useSelect((select) => select(STORE_NAME).getIsActive());
	},
	GET_IS_SAVED() {
		return useSelect((select) => select(STORE_NAME).getIsSaved());
	},
};
