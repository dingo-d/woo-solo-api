/* eslint-disable camelcase, no-unused-vars */
/* global wp */
/* eslint operator-linebreak: ["error", "after"] */

const {useState} = wp.element;
const {useDispatch} = wp.data;
const {__} = wp.i18n;
const {apiFetch} = wp;

const {
	Button,
	PanelBody,
	PanelRow,
	Spinner,
} = wp.components;

// Store
import {STORE_NAME} from '../../store/store';

export const DebuggingPanel = () => {
	const {setErrors} = useDispatch(STORE_NAME);

	const [apiResponse, setApiResponse] = useState('');
	const [isRequestPending, setIsRequestPending] = useState(false);

	const callApi = async () => {
		setApiResponse('');
		setErrors({});
		setIsRequestPending(! isRequestPending);

		try {
			const response = await apiFetch({path: '/woo-solo-api/v1/solo-account-details'});
			setApiResponse(response);
			setIsRequestPending(false);
		} catch (error) {
			setErrors({apiError: error});
			setApiResponse(error);
			setIsRequestPending(false);
		} finally {
			setIsRequestPending(false);
		}
	};

	const deleteTransient = async () => {
		setApiResponse('');
		setErrors({});
		setIsRequestPending(! isRequestPending);

		try {
			const response = await apiFetch({path: '/woo-solo-api/v1/solo-clear-currency-transient'});

			setApiResponse(response ?
				__('Exchange rate transient is cleared.', 'woo-solo-api') :
				__('Exchange rate transient wasn\'t cleared.', 'woo-solo-api') // eslint-disable-line
			);
			setIsRequestPending(false);
		} catch (error) {
			setErrors({apiError: error});
			setApiResponse(error);
			setIsRequestPending(false);
		} finally {
			setIsRequestPending(false);
		}
	};

	return (
		<PanelBody
			title={__('Solo Api Test', 'woo-solo-api')}
			initialOpen={false}
		>
			<PanelRow className='components-panel__row--single'>
				<h4>{__('This serves for testing purposes only', 'woo-solo-api')}</h4>
				<p className='components-base-control__notice'>
					{__('Pressing the button will make a request to your Solo API account, and will list all the invoices or offers you have', 'woo-solo-api')}
				</p>
				<pre className='components-base-control__code'>
					<code>
						{isRequestPending &&
							<Spinner />
						}
						{apiResponse !== '' &&
							apiResponse
						}
					</code>
				</pre>
				<Button
					isPrimary
					isLarge
					disabled={isRequestPending}
					onClick={callApi}
				>
					{__('Make a request', 'woo-solo-api')}
				</Button>
				<p className='components-base-control__notice'>
					{__('Pressing the button will clear the Croatian Central Bank (HNB) API transient.', 'woo-solo-api')}
				</p>
				<Button
					isPrimary
					isLarge
					disabled={isRequestPending}
					onClick={deleteTransient}
				>
					{__('Delete exchange rate transient', 'woo-solo-api')}
				</Button>
			</PanelRow>
		</PanelBody>
	);
};
