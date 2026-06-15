/**
 * External dependencies.
 */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

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
