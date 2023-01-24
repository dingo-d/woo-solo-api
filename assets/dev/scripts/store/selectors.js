export const selectors = {
	getSettings(state) {
		return state.settings;
	},
	getDbOrders(state) {
		return state.dbOrders;
	},
	getErrors(state) {
		return state.errors;
	},
	getIsActive(state) {
		return state.isActive;
	},
	getIsSaved(state) {
		return state.isSaved;
	},
};
