/* eslint-disable camelcase, no-unused-vars */
/* global wp, window */
/* eslint operator-linebreak: ["error", "after"] */

import {serialize} from 'php-serialize';
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const {useSelect, useDispatch} = wp.data;
const {__} = wp.i18n;
const {models} = wp.api;
const {
	Button,
	Spinner,
} = wp.components;

// Components
import {Notification} from './Notification';
import {SettingsPanel} from './SettingsPanel';
import {OrderPanel} from './OrderPanel';
import {Popup} from './Popup';

// Store
import {STORE_NAME} from '../store/store';

export const App = () => {
	const storeSettings = useSelect((select) => select(STORE_NAME).getSettings());
	const isActive = useSelect((select) => select(STORE_NAME).getIsActive());

	const isLoading = storeSettings?.isLoading ?? true;

	const {setErrors, setIsActive, setIsSaved} = useDispatch(STORE_NAME);

	// Can we do this better?
	const objectHasEmptyProperties = (object) => {
		for (const key in object) {
			if (object?.hasOwnProperty(key)) {
				if (object[key] !== null && object[key] !== '' && typeof object[key] !== 'undefined') {
					return false;
				}
			}
		}

		return true;
	};

	const settingsListLength = Object.keys(storeSettings).length;

	const saveSettings = () => {
		setIsActive(true);
		setErrors({}); // Reset the errors.

		// No changes from the original, bail out.
		if (settingsListLength === 0) {
			setIsActive(false);
			setIsSaved(false);
			return;
		}

		// Update store.
		const options = Object.keys(storeSettings)
			.filter((key) => key !== 'isLoading' && key !== 'settingsRefs')
			.reduce((obj, key) => {
				if (! objectHasEmptyProperties(storeSettings)) {
					if (key === 'solo_api_available_gateways' || key === 'solo_api_mail_gateway') {
						obj[key] = serialize(storeSettings[key]);
					} else {
						obj[key] = storeSettings[key];
					}
				}

				return obj;
			}, {});

		const settingsModel = new models.Settings();

		settingsModel.save(options, {
			success: () => {
				setIsActive(false);
				setIsSaved(true);
			},
			error: (model, res) => {
				const errorsRes = res.responseJSON.data.params;
				const refName = Object.keys(errorsRes)[0];
				const refs = storeSettings.settingsRefs;

				window.scrollTo({
					top: refs.current[refName].offsetTop - 60,
					left: 0,
					behavior: 'smooth',
				});

				setIsActive(false);
				setIsSaved(false);
				setErrors({...errorsRes});
			},
		});
	};

	const optionsWrapperClass = classnames({
		'options-wrapper': true,
		'is-loading': isLoading,
	});

	return (
		<>
			<Notification/>
			{isLoading ?
				<Spinner/> :
				<>
					<div className={optionsWrapperClass}>
						{settingsListLength > 0 &&
							<SettingsPanel />
						}
						<div className='options-wrapper__item'>
							<Button
								isPrimary
								isLarge
								disabled={isActive}
								onClick={saveSettings}
							>
								{__('Save settings', 'woo-solo-api')}
							</Button>
							{isActive ? <Spinner/> : ''}
							<Popup />
						</div>
					</div>
					<div className='options-wrapper options-wrapper--separated'>
						<OrderPanel />
					</div>
				</>
			}
		</>
	);
};
