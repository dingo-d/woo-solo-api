/* eslint-disable camelcase */
import { useState } from 'react';

const {apiFetch} = wp;
const {__} = wp.i18n;

const {
	Button,
	ExternalLink,
	PanelBody,
	PanelRow,
} = wp.components;

export const OrderPanel = ({orders}) => {
	const [isResent, setIsResent] = useState(false);

	const resendEmail = (orderID) => {
		apiFetch({
			path: '/woo-solo-api/v1/resend-customer-email',
			method: 'POST',
			data: {
				orderID
			}
		}).then(res => {
			console.log('API call made')
			console.log(res)
			setIsResent(true);
		});
	};

	return (
		<PanelBody
			title={__('Solo API order details', 'woo-solo-api')}
			initialOpen={false}
			classnames={'separated'}
		>
			<PanelRow>
				<div className="details-table">
					<div className="details-table__element details-table__element--heading">{__('ID', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('WooCommerce Order ID', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('Solo ID', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('Customer Email', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('Is sent to Solo API', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('Is pdf sent to customer', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('API response errors', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('Created at', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('Updated at', 'woo-solo-api')}</div>
					<div className="details-table__element details-table__element--heading">{__('Resend the customer email', 'woo-solo-api')}</div>
					{orders.map((el) => {
						return <>
							<div className="details-table__element">{el.id}</div>
							<div className="details-table__element">
								<ExternalLink href={`/wp-admin/post.php?post=${el.order_id}&action=edit`}>{el.order_id}</ExternalLink>
							</div>
							<div className="details-table__element">{el.solo_id || ''}</div>
							<div className="details-table__element">{el.customer_email}</div>
							<div className="details-table__element">{el.is_sent_to_api === '1' ? '✅' : '❌'}</div>
							<div className="details-table__element">{el.is_sent_to_user === '1' ? '✅' : '❌'}</div>
							<div className="details-table__element">{el.error_message}</div>
							<div className="details-table__element">{el.created_at}</div>
							<div className="details-table__element">{el.updated_at}</div>
							<div className="details-table__element">
								<Button
									isPrimary
									disabled={isResent}
									onClick={resendEmail(el.order_id)}
								>
									{__('Resend PDF email', 'woo-solo-api')}
								</Button>
							</div>
						</>
					})}
				</div>
			</PanelRow>
		</PanelBody>
	)
}
