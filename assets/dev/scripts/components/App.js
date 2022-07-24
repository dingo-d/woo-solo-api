/* eslint-disable camelcase */

import classnames from 'classnames';

// Components
import {Notification} from './Notification';
import {SettingsPanels} from './SettingsPanels';
import {OrderPanel} from './OrderPanel';

// Store
import {STORE_NAME} from "../store/store";

/**
 * WordPress dependencies
 */
const {
	withSelect,
} = wp.data;

const {
	Spinner,
} = wp.components;


const Main = (props) => {
	const {
		dbOrders,
	} = props;

	let isLoading = true;

	if (dbOrders.length > 0) {
		isLoading = false;
	}

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
						<OrderPanel orders={dbOrders}/>
					</>
				}
			</div>
		</>
	);
};

export const App = withSelect((select) => {
	return {
		dbOrders: select(STORE_NAME).getDbOrders(),
	};
})(Main);


