/* eslint-disable camelcase, no-unused-vars */
/* global wp, window */
/* eslint operator-linebreak: ["error", "after"] */

import classnames from 'classnames';

const {apiFetch} = wp;
const {__} = wp.i18n;
const {useState} = wp.element;
const {useSelect, useDispatch} = wp.data;

const {
	Button,
	ExternalLink,
	PanelBody,
	PanelRow,
	Snackbar,
} = wp.components;

import {STORE_NAME} from '../store/store';

export const OrderPanel = () => {
	const dbOrders = useSelect((select) => select(STORE_NAME).getDbOrders());
	const errors = useSelect((select) => select(STORE_NAME).getErrors());
	const {setErrors} = useDispatch(STORE_NAME);

	const hasErrors = errors?.dbOrderErrors?.message.length > 0;

	const [apiResponse, setApiResponse] = useState('');
	const [isRequestPending, setIsRequestPending] = useState(false);

	const noticeClass = classnames({
		'order-table-notice': true,
		'has-error': hasErrors,
	});

	const resendEmail = async (orderID, ev) => {
		setApiResponse('');
		setErrors({});
		setIsRequestPending(! isRequestPending);

		try {
			const response = await apiFetch({
				path: '/woo-solo-api/v1/resend-customer-email',
				method: 'POST',
				data: {
					orderID,
				},
			});

			setApiResponse(response);
			setIsRequestPending(false);
		} catch (error) {
			setErrors({dbOrderErrors: error});
			setIsRequestPending(false);
		} finally {
			setIsRequestPending(false);
		}
	};

	return (
		<PanelBody
			title={__('Solo API order details', 'woo-solo-api')}
			initialOpen={true}
			classnames={'separated'}
		>
			<PanelRow>
				<div className='order-table'>
					<div className='order-table__element order-table__element--heading'>{__('ID', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('WooCommerce Order ID', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('Solo ID', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('Customer Email', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('Is sent to Solo API', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('Is pdf sent to customer', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('API response errors', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('Created at', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('Updated at', 'woo-solo-api')}</div>
					<div className='order-table__element order-table__element--heading'>{__('Resend the customer email', 'woo-solo-api')}</div>
					{dbOrders.map((el) => {
						return <>
							<div className='order-table__element'>{el.id}</div>
							<div className='order-table__element'>
								<ExternalLink href={`/wp-admin/post.php?post=${el.order_id}&action=edit`}>{el.order_id}</ExternalLink>
							</div>
							<div className='order-table__element'>{el.solo_id || ''}</div>
							<div className='order-table__element'>{el.customer_email}</div>
							<div className='order-table__element'>{el.is_sent_to_api === '1' ? '✅' : '❌'}</div>
							<div className='order-table__element'>{el.is_sent_to_user === '1' ? '✅' : '❌'}</div>
							<div className='order-table__element'>{el.error_message}</div>
							<div className='order-table__element'>{el.created_at}</div>
							<div className='order-table__element'>{el.updated_at}</div>
							<div className='order-table__element'>
								<Button
									isPrimary
									disabled={isRequestPending}
									onClick={() => resendEmail(el.order_id)}
								>
									{__('Resend PDF email', 'woo-solo-api')}
								</Button>
							</div>
						</>;
					})}
				</div>
				<div className={noticeClass}>
					{
						hasErrors &&
						errors?.dbOrderErrors?.message
					}
					{
						apiResponse &&
						apiResponse
					}
				</div>
				<Snackbar className={isRequestPending ? 'is-visible' : 'is-hidden'}>{__('Request sent', 'woo-solo-api')}
				</Snackbar>
			</PanelRow>
		</PanelBody>
	);
};
