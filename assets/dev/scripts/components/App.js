/* eslint-disable camelcase */

import classnames from 'classnames';

// Components
import {Notification} from './Notification';
import {SettingsPanels} from './SettingsPanels';
// import {OrderPanel} from './OrderPanel';

/**
 * WordPress dependencies
 */
const {useSelect} = wp.data;

// Store
import {STORE_NAME} from "../store/store";

const {
	Spinner,
} = wp.components;

export const App = () => {
	const dbOrders = useSelect((select) => select(STORE_NAME).getDbOrders());
	const settings = useSelect((select) => select(STORE_NAME).getSettings());

	const isLoading = settings?.isLoading ?? true;

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
						<SettingsPanels />
						{/*<OrderPanel orders={dbOrders}/>*/}
					</>
				}
			</div>
		</>
	);
};
