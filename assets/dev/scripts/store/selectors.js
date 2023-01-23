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
	getIsSaving(state) {
		return state.isSaving;
	},
	getIsSaved(state) {
		return state.isSaved;
	},
};
