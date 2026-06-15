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

registerBlockType( metadata, {
	icon,
	edit: () => (
		// eslint-disable-next-line react-hooks/rules-of-hooks
		<div { ...useBlockProps() }>
			<ServerSideRender block="theme/greeting" />
		</div>
	),
	save: () => null,
} );
