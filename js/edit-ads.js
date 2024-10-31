var theList; var theExtraList;
jQuery(function($) {

var dimAfter = function( r, settings ) {
	$('li span.ad-count').each( function() {
		var a = $(this);
		var n = parseInt(a.html(),10);
		n = n + ( $('#' + settings.element).is('.' + settings.dimClass) ? 1 : -1 );
		if ( n < 0 ) { n = 0; }
		a.html( n.toString() );
		$('#ad-awaiting-mod')[ 0 == n ? 'addClass' : 'removeClass' ]('count-0');
	});
	$('.post-com-count span.ad-count').each( function() {
		var a = $(this);
		var n = parseInt(a.html(),10);
		var t = parseInt(a.parent().attr('title'), 10);
		if ( $('#' + settings.element).is('.unapproved') ) { // we unapproved a formerly approved ad
			n = n - 1;
			t = t + 1;
		} else { // we approved a formerly unapproved ad
			n = n + 1;
			t = t - 1;
		}
		if ( n < 0 ) { n = 0; }
		if ( t < 0 ) { t = 0; }
		if ( t >= 0 ) { a.parent().attr('title', adminadsL10n.pending.replace( /%i%/, t.toString() ) ); }
		if ( 0 === t ) { a.parents('strong:first').replaceWith( a.parents('strong:first').html() ); }
		a.html( n.toString() );
	});
}

var delAfter = function( r, settings ) {
	$('li span.ad-count').each( function() {
		var a = $(this);
		var n = parseInt(a.html(),10);
		if ( $('#' + settings.element).is('.unapproved') ) { // we deleted a formerly unapproved ad
			n = n - 1;
		} else if ( $(settings.target).parents( 'span.unapprove' ).size() ) { // we "deleted" an approved ad from the approved list by clicking "Unapprove"
			n = n + 1;
		}
		if ( n < 0 ) { n = 0; }
		a.html( n.toString() );
		$('#ad-awaiting-mod')[ 0 == n ? 'addClass' : 'removeClass' ]('count-0');
	});
	$('.post-com-count span.ad-count').each( function() {
		var a = $(this);
		if ( $('#' + settings.element).is('.unapproved') ) { // we deleted an unapproved ad, decrement pending title
			var t = parseInt(a.parent().attr('title'), 10);
			if ( t < 1 ) { return; }
			t = t - 1;
			a.parent().attr('title', adminadsL10n.pending.replace( /%i%/, t.toString() ) );
			if ( 0 === t ) { a.parents('strong:first').replaceWith( a.parents('strong:first').html() ); }
			return;
		}
		var n = parseInt(a.html(),10) - 1;
		a.html( n.toString() );
	});

	if ( theExtraList.size() == 0 || theExtraList.children().size() == 0 ) {
		return;
	}

	theList.get(0).wpList.add( theExtraList.children(':eq(0)').remove().clone() );
	$('#get-extra-ads').submit();
}

theExtraList = $('#the-extra-ad-list').wpList( { alt: '', delColor: 'none', addColor: 'none' } );
theList = $('#the-ad-list').wpList( { alt: '', dimAfter: dimAfter, delAfter: delAfter, addColor: 'none' } );

} );
