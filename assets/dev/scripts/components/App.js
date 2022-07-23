/* eslint-disable camelcase */

import classnames from 'classnames';

// Components
import {Notification} from './Notification';
import {SettingsPanels} from './SettingsPanels';
import {OrderPanel} from './OrderPanel';

// Store
import {STORE_NAME} from "../store";

/**
 * WordPress dependencies
 */
const {
	dispatch,
	withSelect,
	subscribe,
	select,
} = wp.data;

const {
	Spinner,
} = wp.components;

export const App = () => {
	const isLoading = withSelect((select) => {
		return select(STORE_NAME).getIsLoading();
	});

	setTimeout(() => {
		dispatch(STORE_NAME).unsetIsLoading();
	}, 3000);

	console.log(isLoading);

	const optionsWrapperClass = classnames({
		'options-wrapper': true,
		'is-loading': isLoading,
	});

	return (
		<>
			<Notification/>
			<div className={optionsWrapperClass}>
				{isLoading ?
					<Spinner/> :
					<>
						<SettingsPanels/>
						<OrderPanel/>
					</>
				}
			</div>
		</>
	);
};
