/**
 * External dependencies.
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import icon from './icon';
import metadata from './block.json';
import Edit from './edit';

registerBlockType( metadata, {
	icon,
	edit: Edit,
	save: () => null,
} );
