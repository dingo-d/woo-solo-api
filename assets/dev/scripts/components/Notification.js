/* eslint-disable camelcase */
import React from 'react';

const {__} = wp.i18n;

const {ExternalLink} = wp.components;

export const Notification = () => {
	return (
		<div className='notice'>
			<p>{__('For more details on the options you can read the official SOLO API documentation here: ', 'woo-solo-api')}
				<ExternalLink
					href='https://solo.com.hr/api-dokumentacija'>https://solo.com.hr/api-dokumentacija</ExternalLink>
			</p>
		</div>
	)
}
