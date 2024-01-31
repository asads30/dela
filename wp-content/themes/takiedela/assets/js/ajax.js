jQuery( function( $ ) {
	var button = $( '#loadmore' ),
	    button2 = $( '#filter-btn' ),
			paged = button.data( 'paged' ),
			maxPages = button.data( 'max_pages' );

	button.click( function( event ) {
		event.preventDefault();
		if( ! $( 'body' ).hasClass( 'loading' ) ) {
			$.ajax({
				type : 'POST',
				url : misha.ajax_url,
				data : {
					paged : paged,
					action : 'loadmore',
					taxonomy : button.data( 'taxonomy' ),
					term_id : button.data( 'term_id' ),
					pagenumlink : button.data( 'pagenumlink' )
				},
				dataType: 'json',
				beforeSend : function( xhr ) {
					button.text( 'Загружаем...' );
					$( 'body' ).addClass( 'loading' );
				},
				success : function( data ){
					paged++;
					$('.posts').append( data.posts );
					$( '.pagination' ).html( data.pagination );
					button.text( 'Загрузить ещё' );
					if( paged == maxPages ) {
						button.remove();
					}
					$( 'body' ).removeClass( 'loading' );
				}
			});
		}
	} );

  button2.click( function( event ) {
		event.preventDefault();
    let filter_date = $('#filter-date').val();
		if( ! $( 'body' ).hasClass( 'loading' ) ) {
			$.ajax({
				type : 'POST',
				url : misha.ajax_url,
				data : {
					filter_date : filter_date,
					action : 'filter'
				},
				dataType: 'json',
				beforeSend : function( xhr ) {
					button2.text( 'Загружаем...' );
					$( 'body' ).addClass( 'loading' );
				},
				success : function( data ){
					$('.posts').html( data.posts );
					button2.text( 'Применить фильтр' );
                    button.remove();
                    $('.pagination').remove();
					$( 'body' ).removeClass( 'loading' );
				}
			});
		}
	} );
} );