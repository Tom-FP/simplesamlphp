<?php

/**
 * SAML 2.0 IdP configuration for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-hosted
 */

$metadata['__DYNAMIC:1__'] = [
	 'host' => '__DEFAULT__',
	 'privatekey' => 'Datafordeler.key.pem',
	 'certificate' => 'Datafordeler.crt.pem',
	 'auth' => 'kdi-authentication',
	'authproc' => [
		1 => [
			'class' => 'saml:AttributeNameID',
			'identifyingAttribute' => 'nameId',
			'Format' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:X509SubjectName',
			'SPNameQualifier' => false
		],
	 ],
	'attributes.NameFormat' => 'urn:oasis:names:tc:SAML:2.0:attrname-format:basic',
	'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:X509SubjectName',
	'saml20.sign.assertion' => true,
	'saml20.sign.response' => false,
	'redirect.sign' => true,
	'signature.algorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
	'attributes' => [
		'dk:gov:saml:attribute:CvrNumberIdentifier',
		'dk:gov:saml:attribute:KombitSpecVer',
		'dk:gov:saml:attribute:SpecVer',
		'dk:gov:saml:attribute:AssuranceLevel',
		'dk:gov:saml:attribute:Privileges_intermediate'
	]
];
