/**
 * WordPress dependencies
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

const Edit = ( props ) => {
	const { attributes, setAttributes } = props;

	const blockProps = useBlockProps( {
		className: 'wp-block-theme-authentication-cards',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title="Instellingen">
					<SelectControl
						label="Inlogprovider"
						value={ attributes.provider }
						options={ [
							{ label: 'Signicat OpenID', value: 'owc-signicat-openid' },
							{ label: 'Anoigo OpenID', value: 'owc-anoigo-openid' },
						] }
						onChange={ ( value ) =>
							setAttributes( { provider: value } )
						}
					/>
					<ToggleControl
						label="Toon Digid"
						checked={ !! attributes.displayDigid }
						onChange={ ( value ) =>
							setAttributes( { displayDigid: value } )
						}
					/>
					<ToggleControl
						label="Toon eHerkenning"
						checked={ !! attributes.displayEherkenning }
						onChange={ ( value ) =>
							setAttributes( { displayEherkenning: value } )
						}
					/>
					<ToggleControl
						label="Toon eIDAS"
						checked={ !! attributes.displayEidas }
						onChange={ ( value ) =>
							setAttributes( { displayEidas: value } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ServerSideRender
					block="theme/authentication-cards"
					attributes={ attributes }
				/>
			</div>
		</>
	);
};

export default Edit;
