const {__} = wp.i18n;

export const paymentTypeOptions = [
	{value: '1', label: __('Transactional account', 'woo-solo-api')},
	{value: '2', label: __('Cash', 'woo-solo-api')},
	{value: '3', label: __('Cards', 'woo-solo-api')},
	{value: '4', label: __('Cheque', 'woo-solo-api')},
	{value: '5', label: __('Other', 'woo-solo-api')},
];
