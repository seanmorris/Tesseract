<?php
require('Tesseract.php');

//Define an array of fighter jets
//Lengths are in meters
//Weights are in Kilograms
$jets = array(
	(object)array(
		'name'				=> 'F-15 Eagle'
		, 'yearIntroduced'	=> 1976
		, 'wingspan'		=> 13
		, 'weight'			=> 12700
		, 'length'			=> 19
	)
	, (object)array(
		'name'				=> 'Yakovlev Yak-38'
		, 'yearIntroduced'	=> 1976
		, 'wingspan'		=> 7
		, 'weight'			=> 7385
		, 'length'			=> 16
	)
	, (object)array(
		'name'				=> 'Grumman F-14 Tomcat'
		, 'yearIntroduced'	=> 1974
		, 'wingspan'		=> 19
		, 'weight'			=> 19838
		, 'length'			=> 19
	)
);

//Create a tesseract with 4 dimensions
$tesseract = new Tesseract(4);

//Label each dimension
$tesseract->label(
	'wingspan'
	, 'yearIntroduced'
	, 'weight'
	, 'length'
);

//Add the planes to the tesseract
foreach($jets as $jet)
{
	$tesseract->insert(
		$jet->wingspan
		, $jet->yearIntroduced
		, $jet->weight
		, $jet->length
		, $jet
	);
}

//Get a tesseract rotated to have yearIntroduced on top
$yearOnTop = $tesseract->rotate('yearIntroduced', 'wingspan');

//Get a tesseract rotated to have weight on top
$weightOnTop = $tesseract->rotate('weight', 'wingspan');

//Get a tesseract rotated to have weight on top
$lengthOnTop = $tesseract->rotate('length', 'wingspan');

var_dump(
	//Get a list of lists keyed by wingspan
	"Wingspans"
	, $tesseract->flatten()->unwrap()
	//Get a list  of lists keyed by year
	, "Years"
	, $yearOnTop->flatten()->unwrap()
	//Get a list  of lists keyed by weight
	, "Weights"
	, $weightOnTop->flatten()->unwrap()
	//Get a list  of lists keyed by length
	, "Lengths"
	, $lengthOnTop->flatten()->unwrap()
);
