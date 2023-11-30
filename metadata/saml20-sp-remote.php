<?php

/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

/*
 * Example SimpleSAMLphp SAML 2.0 SP - Du angiver Entity ID for SP som nøgle i $metadata array.
 */

$metadata['https://saml.oiosaml3-net.dk'] = [
	// 'name' => 'Mit fagsystem',
	// 'entityId' => 'https://fk-test-01.formpipe.dk',
    'AssertionConsumerService' => 'https://fk-test-01.formpipe.dk/WebDemoSite/login.ashx',
	'certificate' => 'C:\simplesamlphp\cert\serviceprovider.pem', 
	'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:X509SubjectName',
	'saml20.sign.assertion' => true, 
	'saml20.sign.response' => false, 
	'signature.algorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256', 
	'SingleLogoutService' => 'https://fk-test-01.formpipe.dk/WebDemoSite/logout.ashx',
	'authproc' => [
	1 => [
			'class' => 'saml:AttributeNameID',
			'identifyingAttribute' => 'nameId',
			'Format' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:X509SubjectName',
			'SPNameQualifier' => false
		],	
    ],    
];



