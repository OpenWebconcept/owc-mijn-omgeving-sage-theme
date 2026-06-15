/**
 * WordPress dependencies
 */
import {
	registerBlockVariation,
	unregisterBlockVariation,
} from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

const unusedVariations = [
	{
		block: 'core/group',
		variation: 'group',
	},
	{
		block: 'core/group',
		variation: 'group-row',
	},
];

domReady( () => {
	// Override core/group to start with a background color
	registerBlockVariation( 'core/group', {
		isDefault: true,
		isActive: [ 'className' ],
		name: 'group-with-background',
		attributes: {
			backgroundColor: 'white',
		},
	} );

	unusedVariations.forEach( ( { block, variation } ) => {
		unregisterBlockVariation( block, variation );
	} );
} );
