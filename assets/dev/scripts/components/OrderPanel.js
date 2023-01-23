/* eslint-disable camelcase, no-unused-vars */
/* global wp, window */
/* eslint operator-linebreak: ["error", "after"] */

const {apiFetch} = wp;
const {__} = wp.i18n;
const {useState} = wp.element;
const {useSelect} = wp.data;

const {
	Button,
	ExternalLink,
	PanelBody,
	PanelRow,
} = wp.components;

import {STORE_NAME} from '../store/store';

export const OrderPanel = () => {
	const dbOrders = useSelect((select) => select(STORE_NAME).getDbOrders());
	const [isResent, setIsResent] = useState(false);

	const resendEmail = async (orderID) => {
		try {
			const response = await apiFetch({
				path: '/woo-solo-api/v1/resend-customer-email',
				method: 'POST',
				data: {
					orderID,
				},
			});
		} catch {
			setIsResent(true);
		}
	};

	return (
		<PanelBody
			title={__('Solo API order details', 'woo-solo-api')}
			initialOpen={false}
			classnames={'separated'}
		>
			<PanelRow>
				<div className='details-table'>
					<div className='details-table__element details-table__element--heading'>{__('ID', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('WooCommerce Order ID', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('Solo ID', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('Customer Email', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('Is sent to Solo API', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('Is pdf sent to customer', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('API response errors', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('Created at', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('Updated at', 'woo-solo-api')}</div>
					<div className='details-table__element details-table__element--heading'>{__('Resend the customer email', 'woo-solo-api')}</div>
					{dbOrders.map((el) => {
						return <>
							<div className='details-table__element'>{el.id}</div>
							<div className='details-table__element'>
								<ExternalLink href={`/wp-admin/post.php?post=${el.order_id}&action=edit`}>{el.order_id}</ExternalLink>
							</div>
							<div className='details-table__element'>{el.solo_id || ''}</div>
							<div className='details-table__element'>{el.customer_email}</div>
							<div className='details-table__element'>{el.is_sent_to_api === '1' ? '✅' : '❌'}</div>
							<div className='details-table__element'>{el.is_sent_to_user === '1' ? '✅' : '❌'}</div>
							<div className='details-table__element'>{el.error_message}</div>
							<div className='details-table__element'>{el.created_at}</div>
							<div className='details-table__element'>{el.updated_at}</div>
							<div className='details-table__element'>
								<Button
									isPrimary
									disabled={isResent}
									onClick={resendEmail(el.order_id)}
								>
									{__('Resend PDF email', 'woo-solo-api')}
								</Button>
							</div>
						</>;
					})}
				</div>
			</PanelRow>
		</PanelBody>
	);
};
