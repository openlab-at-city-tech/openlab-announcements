import './frontend.scss';

document.addEventListener( 'DOMContentLoaded', () => {
	const handleButtonClose = ( announcementId ) => {
		const announcementEl = document.getElementById( `openlab-tip-${ announcementId }` );
		announcementEl.classList.add( 'fade-out' );
		setTimeout( () => {
			announcementEl.classList.add( 'hidden' );
		}, 500 );

		// Send an AJAX request to save this setting.
		const data = {
			action: 'openlab_announcements_hide_tip',
			announcementId,
			nonce: document.getElementById( 'openlab-tip-nonce' ).value,
		};

		const formData = new URLSearchParams( data );

		fetch( window.ajaxurl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: formData.toString(),
		} )
			.then( ( response ) => {
				if ( ! response.ok ) {
					throw new Error( 'Network response was not ok' );
				}
			} )
			.catch( ( error ) => {
				console.error( 'There has been a problem with your fetch operation:', error );
			} );
	}

	const closeButtons = document.querySelectorAll( '.openlab-tip-close' );
	closeButtons.forEach( ( closeButton ) => {
		closeButton.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			const button = e.target.closest( '.openlab-tip-close' );
			handleButtonClose( button.dataset.announcementId );
		} );
	} );
} );
