/* eslint-disable camelcase, no-unused-vars */
/* global wp, window */
/* eslint operator-linebreak: ["error", "after"] */

import {actions} from './actions';
import {controls} from './controls';
import {reducer} from './reducer';
import {resolvers} from './resolvers';
import {selectors} from './selectors';

import {createReduxStore, register} from '@wordpress/data';

export const STORE_NAME = 'woo-solo-api/store';

// Register the store.
export const setStore = () => {
	if (typeof window?.wooSoloApi === 'undefined') {
		window.wooSoloApi = {};
	}

	const store = createReduxStore(
		STORE_NAME,
		{
			selectors,
			actions,
			resolvers,
			reducer,
			controls,
		} // eslint-disable-line
	);

	register(store);
};

// Set global window data for easier debugging.
export const setStoreGlobalWindow = () => {
	if (typeof window?.wooSoloApi?.store === 'undefined') {
		window.wooSoloApi.store = {};
	}

	window.wooSoloApi.store.name = STORE_NAME;
};
