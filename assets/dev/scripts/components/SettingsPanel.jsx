/* eslint-disable camelcase, no-unused-vars */
/* global wp */
/* eslint operator-linebreak: ["error", "after"] */

import {TokenPanel} from './Settings/TokenPanel';
import {GeneralSettingsPanel} from './Settings/GeneralSettingsPanel';
import {AdditionalSettingsPanel} from './Settings/AdditionalSettingsPanel';
import {EmailSettingsPanel} from './Settings/EmailSettingsPanel';

export const SettingsPanel = () => {
	return (
		<>
			<TokenPanel />
			<GeneralSettingsPanel />
			<AdditionalSettingsPanel />
			<EmailSettingsPanel />
		</>
	);
};
