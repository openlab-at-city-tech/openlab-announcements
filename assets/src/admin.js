/* global ajaxurl, OpenLabAnnouncementsAdmin */
import { __ } from '@wordpress/i18n';

import './admin.scss';

// Create 'Screen options' checkbox for OpenLab News panel.
const { panelIsVisible } = OpenLabAnnouncementsAdmin
const checkboxEl = document.createElement( 'input' );
checkboxEl.setAttribute( 'type', 'checkbox' );
checkboxEl.setAttribute( 'id', 'openlab_news_panel-hide' );
checkboxEl.checked = panelIsVisible;

const checkboxLabelEl = document.createElement( 'label' );
checkboxLabelEl.setAttribute( 'for', 'openlab_news_panel-hide' );
checkboxLabelEl.textContent = __( 'OpenLab News', 'openlab-announcements' );

checkboxLabelEl.prepend( checkboxEl );

const wpWelcomePanelHide = document.querySelector( 'label[for="wp_welcome_panel-hide"]' );
wpWelcomePanelHide.after( checkboxLabelEl );

// Move the OpenLab News panel out of the #welcome-panel element.
const openlabNewsPanel = document.querySelector( '#openlab-news-panel' );
const welcomePanel = document.querySelector( '#welcome-panel' );
welcomePanel.after( openlabNewsPanel );

// Hide OpenLab News panel if checkbox is checked.
const openlabNewsPanelHide = document.querySelector( '#openlab_news_panel-hide' );
openlabNewsPanelHide.addEventListener( 'change', () => {
	if ( openlabNewsPanelHide.checked ) {
		openlabNewsPanel.classList.remove( 'hidden' );
	} else {
		openlabNewsPanel.classList.add( 'hidden' );
	}

	// Send an AJAX request to save this setting.
	const data = {
		action: 'openlab_announcements_hide_panel',
		visible: openlabNewsPanelHide.checked,
		nonce: document.getElementById( 'openlab-news-panel-nonce' ).value,
	};

	fetch( ajaxurl, {
		method: 'POST',
		body: new URLSearchParams( data )
	} );
} );
