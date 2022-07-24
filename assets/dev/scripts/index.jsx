const {render} = wp.element;

import {setStoreGlobalWindow, setStore} from './store/store';
import {App} from './components/App';

// Set all store values.
setStore();
setStoreGlobalWindow();

render(
	<App/>,
	document.getElementById('solo-api-options-page')
);
