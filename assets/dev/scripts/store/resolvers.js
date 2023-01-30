import {actions} from './actions';

export const resolvers = {
	* getSettings() {
		const path = '/wp/v2/settings';
		const settingsRes = yield actions.fetchFromApi(path);

		const settings = Object.keys(settingsRes)
			.filter((setting) => setting.includes('solo_api_'))
			.reduce((obj, key) => {
				return Object.assign(obj, {
					[key]: settingsRes[key],
				});
			}, {});

		return actions.setSettings(settings);
	},
	* getDbOrders() {
		const path = '/woo-solo-api/v1/solo-order-details';
		const dbData = yield actions.fetchFromApi(path);

		return actions.setDbOrders(dbData);
	},
	* getErrors() {
		const errors = yield actions.getErrors();

		return actions.setErrors(errors);
	},
	* getIsActive() {
		const isActive = yield actions.getIsActive();

		return actions.setIsActive(isActive);
	},
	* getIsSaved() {
		const isSaved = yield actions.getIsSaved();

		return actions.setErrors(isSaved);
	},
};

