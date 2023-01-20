/* eslint-disable camelcase */

import {serialize} from 'php-serialize';
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const {useState, useRef, createRef} = wp.element;
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
import {Popup} from "./Popup";

// Store
import {STORE_NAME} from "../store/store";

export const App = () => {
	const storeSettings = useSelect((select) => select(STORE_NAME).getSettings());
	const isLoading = storeSettings?.isLoading ?? true;

	const [errors, setErrors] = useState({});
	const [hasErrors, setHasErrors] = useState(false);
	const [isSaving, setIsSaving] = useState(false);
	const [savedSettings, setSavedSettings] = useState(storeSettings);

	const {setSettings} = useDispatch(STORE_NAME);

	const objectHasEmptyProperties = (object) => {
		for (const key in object) {
			if (object?.hasOwnProperty(key)) {
				if (object[key] != null && object[key] !== '' && typeof(object[key]) !== 'undefined') {
					return false;
				}
			}
		}

		return true;
	}

	// Ref map that we'll pass to the Settings panel component.
	const refs = new Map();

	if (Object.keys(storeSettings).length > 0) {
		Object.keys(storeSettings)
			.filter(setting => setting.startsWith('solo'))
			.forEach((setting) => {
				const elRef = createRef();
				elRef.current = setting;

				refs.set(setting, elRef)
			});
	}

	const saveSettings = () => {
		setIsSaving(isSaving => !isSaving);

		// No changes from the original, bail out.
		if (Object.keys(savedSettings).length === 0) {
			setIsSaving(isSaving => !isSaving);
			return;
		}

		// Update store.
		const options = Object.keys(savedSettings)
			.filter(key => (key !== 'isLoading' &&
				key !== 'isSaving' &&
				key !== 'hasErrors' &&
				key !== 'errors' &&
				key !== 'apiResponse' &&
				key !== 'dbOrders' &&
				key !== 'isApiRequestOver' &&
				key !== 'isSaved'))
			.reduce((obj, key) => {
				if (!objectHasEmptyProperties(savedSettings)) {
					if (key === 'solo_api_available_gateways' || key === 'solo_api_mail_gateway') {
						obj[key] = serialize(savedSettings[key]);
					} else if (savedSettings[key] === null || typeof savedSettings[key] == 'undefined') {
						obj[key] = defaultState[key];
					} else {
						obj[key] = savedSettings[key];
					}
				}

				return obj;
			}, {});

		const settingsModel = new models.Settings();

		settingsModel.save(options, {
			success: (model, res) => {
				setIsSaving(isSaving => !isSaving);
				setHasErrors(hasErrors => !hasErrors);
				// Object.keys(options).map((option) => {
				//
				// 	// this.setState({
				// 	// 	option: res[option],
				// 	// 	isSaving: false,
				// 	// 	isSaved: true,
				// 	// 	hasErrors: false,
				// 	// });
				// 	//
				// });
			},
			error: (model, res) => {
				const errors = res.responseJSON.data.params;
				setIsSaving(isSaving => !isSaving);
				setHasErrors(hasErrors => !hasErrors);
				setErrors(errors)

				// this.setState({
				// 	errors: errors,
				// 	isSaving: false,
				// 	isSaved: true,
				// 	hasErrors: true,
				// });

				// Should be a React reference, not DOM!
				// const target = document.querySelector(`[name=${Object.keys(errors)[0]}]`);
				// debugger;

				const [refName] = Object.keys(errors);

				const errorRef = refs.get(refName)
				console.log(errors)
				console.log(refName)
				console.log(errorRef)
				console.log(refs)
				errorRef.current.scrollIntoView({ behavior: 'smooth', block: 'start' })

				// window.scrollTo({
				// 	top: target.offsetTop - 30,
				// 	left: 0,
				// 	behavior: 'smooth',
				// });
			}
		});

		// Update store. Do we need this?
		setSettings(savedSettings);
		setIsSaving(isSaving => !isSaving);
	}

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
						{Object.keys(storeSettings).length > 0 &&
							<SettingsPanel
								isSaving={isSaving}
								settings={savedSettings}
								initialSettings={storeSettings}
								errors={errors}
								settingRefs={refs}
								onUpdate={setSavedSettings}
							/>
						}
						<div className='options-wrapper__item'>
							<Button
								isPrimary
								isLarge
								disabled={isSaving}
								onClick={saveSettings}
							>
								{__('Save settings', 'woo-solo-api')}
							</Button>
							{isSaving ? <Spinner/> : ''}
							<Popup isSaved={isSaving} hasErrors={errors?.hasErrors} />
						</div>
					</div>
					<div className="options-wrapper options-wrapper--separated">
						<OrderPanel />
					</div>
				</>
			}
		</>
	);
};
