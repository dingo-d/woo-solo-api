/* eslint-disable camelcase */
import React from 'react';

const {__} = wp.i18n;

const {Snackbar} = wp.components;

export const Popup = ({isSaved, hasErrors}) => {
	return (
		<Snackbar className={isSaved ? 'is-visible' : 'is-hidden'}>
			{
				hasErrors ?
					__('Errors happened during saving', 'woo-solo-api') :
					__('Settings saved', 'woo-solo-api')
			}
		</Snackbar>
	)
}
