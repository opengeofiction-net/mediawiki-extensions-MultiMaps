<?php

namespace MultiMaps;

use MediaWiki\MediaWikiServices;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-30 at 09:48:20.
 */
class LeafletTest extends \MediaWikiTestCase {

	/**
	 * @var Leaflet
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp(): void {
		// See https://wiki.php.net/rfc/precise_float_value
		// Once we require PHP 7.1+, we should remove this and adjust expected values instead.
		if ( version_compare( PHP_VERSION, '7.1', '>=' ) ) {
			$this->setIniSetting( 'serialize_precision', 14 );
		}
		$this->object = new Leaflet();
		parent::setUp();
	}

	public function testParseMarkerInZerro() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ '0,0', 'service=leaflet' ] ) ),
			'{"markers":[{"pos":[{"lat":0,"lon":0}]}],"zoom":14,"center":{"lat":0,"lon":0}}'
		);
	}

	public function testInvalidTileLayer() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ '20,20', 'service=in.valid' ] ) ),
			'{"markers":[{"pos":[{"lat":20,"lon":20}]}],"zoom":14,"center":{"lat":20,"lon":20}}'
		);
	}

	public function testParseOneMarker() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ '55.755786, 37.617633', 'service=leaflet' ] ) ),
			'{"markers":[{"pos":[{"lat":55.755786,"lon":37.617633}]}],"zoom":14,"center":{"lat":55.755786,"lon":37.617633}}'
		);
	}

	public function testParseMarkers() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ '52.429222,13.359375; 53.383328,3.339843; 52.5897,2.548828; 52.855864,3.515625; 53.330872,1.757812', 'service=leaflet' ] ) ),
			'{"markers":[{"pos":[{"lat":52.429222,"lon":13.359375}]},{"pos":[{"lat":53.383328,"lon":3.339843}]},{"pos":[{"lat":52.5897,"lon":2.548828}]},{"pos":[{"lat":52.855864,"lon":3.515625}]},{"pos":[{"lat":53.330872,"lon":1.757812}]}],"bounds":{"ne":{"lat":53.383328,"lon":13.359375},"sw":{"lat":52.429222,"lon":1.757812}}}'
		);
	}

	public function testParseOneMarkerTextOnly() {
		$this->assertEquals(
			$this->object->getMapData( [ '55.755786, 37.617633~~This is text', 'service=leaflet' ] ),
			[
				'markers' => [
					[
						'pos' => [ [ 'lat' => '55.755786', 'lon' => '37.617633' ] ],
						'text' => "<p>This is text\n</p>",
					],
				],
				'zoom' => 14,
				'center' => [ 'lat' => '55.755786', 'lon' => '37.617633' ],
			]
		);
	}

	public function testParseOneMarkerTitleOnly() {
		$this->assertEquals(
			$this->object->getMapData( [ '55.755786, 37.617633~This is title ~', 'service=leaflet' ] ),
			[
				'markers' => [
					[
						'pos' => [ [ 'lat' => '55.755786', 'lon' => '37.617633' ] ],
						'title' => "<p>This is title\n</p>",
					],
				],
				'zoom' => 14,
				'center' => [ 'lat' => '55.755786', 'lon' => '37.617633' ],
			]
		);
	}

	public function testParseOneMarkerTitleText() {
		$this->assertEquals(
			$this->object->getMapData( [ '55.755786, 37.617633~This is title ~ This is text ', 'service=leaflet' ] ),
			[
				'markers' => [
					[
						'pos' => [ [ 'lat' => '55.755786', 'lon' => '37.617633' ] ],
						'title' => "<p>This is title\n</p>",
						'text' => "<p>This is text\n</p>",
					],
				],
				'zoom' => 14,
				'center' => [ 'lat' => '55.755786', 'lon' => '37.617633' ],
			]
		);
	}

	public function testParseOneMarkerNamedTextOnly() {
		$this->assertEquals(
			$this->object->getMapData( [ '55.755786, 37.617633~Text = This is text', 'service=leaflet' ] ),
			[
				'markers' => [
					[
						'pos' => [ [ 'lat' => '55.755786', 'lon' => '37.617633' ] ],
						'text' => "<p>This is text\n</p>",
					],
				],
				'zoom' => 14,
				'center' => [ 'lat' => '55.755786', 'lon' => '37.617633' ],
			]
		);
	}

	public function testParseOneMarkerNamedTitleOnly() {
		$this->assertEquals(
			$this->object->getMapData( [ '55.755786, 37.617633~ title=This is title ~', 'service=leaflet' ] ),
			[
				'markers' => [
					[
						'pos' => [ [ 'lat' => '55.755786', 'lon' => '37.617633' ] ],
						'title' => "<p>This is title\n</p>",
					],
				],
				'zoom' => 14,
				'center' => [ 'lat' => '55.755786', 'lon' => '37.617633' ],
			]
		);
	}

	public function testParseOneMarkerNamedTextAndTile() {
		$this->assertEquals(
			$this->object->getMapData( [ '55.755786, 37.617633~tExT = This is text ~This is title ', 'service=leaflet' ] ),
			[
				'markers' => [
					[
						'pos' => [ [ 'lat' => '55.755786', 'lon' => '37.617633' ] ],
						'title' => "<p>This is title\n</p>",
						'text' => "<p>This is text\n</p>",
					],
				],
				'zoom' => 14,
				'center' => [ 'lat' => '55.755786', 'lon' => '37.617633' ],
			]
		);
	}

	public function testParseOneLine() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ ' ', 'lines = 53.225768 , 23.466796 : 53.956085,29.179687', 'service=leaflet' ] ) ),
			'{"lines":[{"pos":[{"lat":53.225768,"lon":23.466796},{"lat":53.956085,"lon":29.179687}]}],"bounds":{"ne":{"lat":53.956085,"lon":29.179687},"sw":{"lat":53.225768,"lon":23.466796}}}'
		);
	}

	public function testParseLines() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ ' ', 'lines=53.225768,23.466796:53.956085,29.179687:56.46249,31.113281:59.310767,30.673828:60.84491,27.333984:60.930432,21.269531:59.175928,16.611328:56.218923,16.259765:54.110942,19.599609:53.225768,23.466796', 'service=leaflet' ] ) ),
			'{"lines":[{"pos":[{"lat":53.225768,"lon":23.466796},{"lat":53.956085,"lon":29.179687},{"lat":56.46249,"lon":31.113281},{"lat":59.310767,"lon":30.673828},{"lat":60.84491,"lon":27.333984},{"lat":60.930432,"lon":21.269531},{"lat":59.175928,"lon":16.611328},{"lat":56.218923,"lon":16.259765},{"lat":54.110942,"lon":19.599609},{"lat":53.225768,"lon":23.466796}]}],"bounds":{"ne":{"lat":60.930432,"lon":31.113281},"sw":{"lat":53.225768,"lon":16.259765}}}'
		);
	}

	public function testParseOnePolygon() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ ' ', 'polygons=  62.103882,   5.097656 :   58.309488,5.71289 : 58.950008,10.898437', 'service=leaflet' ] ) ),
			'{"polygons":[{"pos":[{"lat":62.103882,"lon":5.097656},{"lat":58.309488,"lon":5.71289},{"lat":58.950008,"lon":10.898437}]}],"bounds":{"ne":{"lat":62.103882,"lon":10.898437},"sw":{"lat":58.309488,"lon":5.097656}}}'
		);
	}

	public function testParsePolygons() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ 'polygons=62.103882,5.097656:58.309488,5.71289:58.950008,10.898437:61.689872,12.832031:63.352129,11.865234:64.129783,13.974609:65.58572,14.326171:68.560383,18.28125:68.528234,20.126953:69.224996,20.566406:69.224996,21.708984:68.624543,23.466796:69.005675,25.40039:69.960439,26.630859:70.140364,28.564453:69.256149,28.828125:69.839621,31.201171:70.757965,29.882812:71.102542,25.576171:70.728979,22.675781:69.990534,18.105468:67.842416,13.095703:68.334375,15.908203:63.704722,9.492187:63.782486,8.613281', 'service=leaflet' ] ) ),
			'{"polygons":[{"pos":[{"lat":62.103882,"lon":5.097656},{"lat":58.309488,"lon":5.71289},{"lat":58.950008,"lon":10.898437},{"lat":61.689872,"lon":12.832031},{"lat":63.352129,"lon":11.865234},{"lat":64.129783,"lon":13.974609},{"lat":65.58572,"lon":14.326171},{"lat":68.560383,"lon":18.28125},{"lat":68.528234,"lon":20.126953},{"lat":69.224996,"lon":20.566406},{"lat":69.224996,"lon":21.708984},{"lat":68.624543,"lon":23.466796},{"lat":69.005675,"lon":25.40039},{"lat":69.960439,"lon":26.630859},{"lat":70.140364,"lon":28.564453},{"lat":69.256149,"lon":28.828125},{"lat":69.839621,"lon":31.201171},{"lat":70.757965,"lon":29.882812},{"lat":71.102542,"lon":25.576171},{"lat":70.728979,"lon":22.675781},{"lat":69.990534,"lon":18.105468},{"lat":67.842416,"lon":13.095703},{"lat":68.334375,"lon":15.908203},{"lat":63.704722,"lon":9.492187},{"lat":63.782486,"lon":8.613281}]}],"bounds":{"ne":{"lat":71.102542,"lon":31.201171},"sw":{"lat":58.309488,"lon":5.097656}}}'
		);
	}

	public function testParseOneRectangle() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ ' ', ' ', ' ', 'rectangles  =51.835777  ,33.83789 : 46.377254 ,23.378906 ', 'service=leaflet' ] ) ),
			'{"rectangles":[{"pos":[{"lat":51.835777,"lon":33.83789},{"lat":46.377254,"lon":23.378906}]}],"bounds":{"ne":{"lat":51.835777,"lon":33.83789},"sw":{"lat":46.377254,"lon":23.378906}}}'
		);
	}

	public function testParseRectangles() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ ' ', 'rectangles  =51.835777  ,33.83789 : 46.377254 ,23.378906 ', 'rectangle= 2", 10°10\'12": 40°, 40°', 'service=leaflet' ] ) ),
			'{"rectangles":[{"pos":[{"lat":51.835777,"lon":33.83789},{"lat":46.377254,"lon":23.378906}]}],"bounds":{"ne":{"lat":51.835777,"lon":33.83789},"sw":{"lat":46.377254,"lon":23.378906}}}'
		);
	}

	public function testParseFalseRectangle() {
		$badrectanglecoord = '10°10°10", 10°10\'12"';
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ ' ', 'rectangles  =51.835777  ,33.83789 : 46.377254 ,23.378906 ', "rectangle=$badrectanglecoord: 40, 40", 'service=leaflet' ] ) ),
			'{"rectangles":[{"pos":[{"lat":51.835777,"lon":33.83789},{"lat":46.377254,"lon":23.378906}]}],"bounds":{"ne":{"lat":51.835777,"lon":33.83789},"sw":{"lat":46.377254,"lon":23.378906}}}'
		);

		$rectangle = new Rectangle();
		$this->assertEquals(
			$this->object->getErrorMessages(),
			[
				\wfMessage( 'multimaps-unable-parse-coordinates', $badrectanglecoord )->escaped(),
				\wfMessage( 'multimaps-unable-create-element', $rectangle->getElementName() )->escaped(),
			]
		);

		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ '; ', 'rectangles  =51.835777  ,33.83789 : 46.377254 ,23.378906 ', "rectangle=40, 40:$badrectanglecoord", 'service=leaflet' ] ) ),
			'{"rectangles":[{"pos":[{"lat":51.835777,"lon":33.83789},{"lat":46.377254,"lon":23.378906}]}],"bounds":{"ne":{"lat":51.835777,"lon":33.83789},"sw":{"lat":46.377254,"lon":23.378906}}}'
		);
		$this->assertEquals(
			$this->object->getErrorMessages(),
			[
				\wfMessage( 'multimaps-unable-parse-coordinates', $badrectanglecoord )->escaped(),
				\wfMessage( 'multimaps-unable-create-element', $rectangle->getElementName() )->escaped(),
			]
		);

		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ 'rectangles  =51.835777  ,33.83789 : 46.377254 ,23.378906 ', "rectangle=40, 40 : $badrectanglecoord : 20,20", 'service=leaflet' ] ) ),
			'{"rectangles":[{"pos":[{"lat":51.835777,"lon":33.83789},{"lat":46.377254,"lon":23.378906}]}],"bounds":{"ne":{"lat":51.835777,"lon":33.83789},"sw":{"lat":46.377254,"lon":23.378906}}}'
		);
		$this->assertEquals(
			$this->object->getErrorMessages(),
			[
				\wfMessage( 'multimaps-square-wrong-number-points', 3 )->escaped(),
				\wfMessage( 'multimaps-unable-create-element', $rectangle->getElementName() )->escaped(),
			]
		);
	}

	public function testParseOneCircle() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ 'circle=57.421294,23.90625 : 326844.605182;', 'service=leaflet' ] ) ),
			'{"circles":[{"radius":[326844.605182],"pos":[{"lat":57.421294,"lon":23.90625}]}],"bounds":{"ne":{"lat":60.362317535513,"lon":29.843589135828},"sw":{"lat":54.480270464487,"lon":18.852584546952}}}'
		);
	}

	public function testParseCircles() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ 'circle=57.421294,23.90625 : 326844.605;', 'circles=40,40:400000', 'service=leaflet' ] ) ),
			'{"circles":[{"radius":[326844.605],"pos":[{"lat":57.421294,"lon":23.90625}]},{"radius":[400000],"pos":[{"lat":40,"lon":40}]}],"bounds":{"ne":{"lat":60.362317533876,"lon":44.961831660938},"sw":{"lat":36.400707261023,"lon":18.852584549564}}}'
		);
	}

	public function testParseFalseCircle() {
		$badradius = 'one km';
		$badcoord = '10°10°12", 10°10\'12"';

		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ 'circle=57.421294,23.90625 : 326844.605;', "circles=40,40:$badradius", 'service=leaflet' ] ) ),
			'{"circles":[{"radius":[326844.605],"pos":[{"lat":57.421294,"lon":23.90625}]}],"bounds":{"ne":{"lat":60.362317533876,"lon":29.843589132223},"sw":{"lat":54.480270466124,"lon":18.852584549564}}}'
		);

		$circle = new Circle();
		$this->assertEquals(
			$this->object->getErrorMessages(),
			[
				\wfMessage( 'multimaps-unable-parse-radius', $badradius )->escaped(),
				\wfMessage( 'multimaps-unable-create-element', $circle->getElementName() )->escaped(),
			]
		);

		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ 'circle=57.421294,23.90625 : 326844.605;', "circles=$badcoord:$badradius", 'service=leaflet' ] ) ),
			'{"circles":[{"radius":[326844.605],"pos":[{"lat":57.421294,"lon":23.90625}]}],"bounds":{"ne":{"lat":60.362317533876,"lon":29.843589132223},"sw":{"lat":54.480270466124,"lon":18.852584549564}}}'
		);
		$this->assertEquals(
			$this->object->getErrorMessages(),
			[
				\wfMessage( 'multimaps-unable-parse-coordinates', $badcoord )->escaped(),
				\wfMessage( 'multimaps-unable-create-element', $circle->getElementName() )->escaped(),
			]
		);

		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ 'circle=57.421294,23.90625 : 326844.605;', "circles=40,40", 'service=leaflet' ] ) ),
			'{"circles":[{"radius":[326844.605],"pos":[{"lat":57.421294,"lon":23.90625}]}],"bounds":{"ne":{"lat":60.362317533876,"lon":29.843589132223},"sw":{"lat":54.480270466124,"lon":18.852584549564}}}'
		);
		$this->assertEquals(
			$this->object->getErrorMessages(),
			[
				\wfMessage( 'multimaps-circle-radius-not-defined' )->escaped(),
				\wfMessage( 'multimaps-unable-create-element', $circle->getElementName() )->escaped(),
			]
		);

		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ 'circle=57.421294,23.90625 : 326844.605;', "circles=40,40:40000:8888", 'service=leaflet' ] ) ),
			'{"circles":[{"radius":[326844.605],"pos":[{"lat":57.421294,"lon":23.90625}]}],"bounds":{"ne":{"lat":60.362317533876,"lon":29.843589132223},"sw":{"lat":54.480270466124,"lon":18.852584549564}}}'
		);
		$this->assertEquals(
			$this->object->getErrorMessages(),
			[
				\wfMessage( 'multimaps-circle-wrong-number-parameters', 3 )->escaped(),
				\wfMessage( 'multimaps-unable-create-element', $circle->getElementName() )->escaped(),
			]
		);
	}

	public function testParseAllElements() {
		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [
				'52.429222,13.359375; 53.383328,3.339843; 52.5897,2.548828;
52.855864,3.515625; 53.330872,1.757812',
				'circle=57.421294,23.90625:326844.605',
				'PoLyGoNs=62.103882,5.097656:58.309488,5.71289:58.950008,10.898437
:61.689872,12.832031:63.352129,11.865234:64.129783,13.974609:65.58572,14.326171:
68.560383,18.28125:68.528234,20.126953:69.224996,20.566406:69.224996,21.708984:
68.624543,23.466796:69.005675,25.40039:69.960439,26.630859:70.140364,28.564453:
69.256149,28.828125:69.839621,31.201171:70.757965,29.882812:71.102542,25.576171:
70.728979,22.675781:69.990534,18.105468:67.842416,13.095703:68.334375,15.908203:
63.704722,9.492187:63.782486,8.613281',
				'LINES=53.225768,23.466796:53.956085,29.179687:56.46249,31.113281
:59.310767,30.673828:60.84491,27.333984:60.930432,21.269531:59.175928,16.611328:
56.218923,16.259765:54.110942,19.599609:53.225768,23.466796',
				'rectangles=51.835777,33.83789:46.377254,23.378906',
				'height=500px',
				'service=leaflet'
			] ) ),
			'{"markers":[{"pos":[{"lat":52.429222,"lon":13.359375}]},{"pos":[{"lat":53.383328,"lon":3.339843}]},{"pos":[{"lat":52.5897,"lon":2.548828}]},{"pos":[{"lat":52.855864,"lon":3.515625}]},{"pos":[{"lat":53.330872,"lon":1.757812}]}],"lines":[{"pos":[{"lat":53.225768,"lon":23.466796},{"lat":53.956085,"lon":29.179687},{"lat":56.46249,"lon":31.113281},{"lat":59.310767,"lon":30.673828},{"lat":60.84491,"lon":27.333984},{"lat":60.930432,"lon":21.269531},{"lat":59.175928,"lon":16.611328},{"lat":56.218923,"lon":16.259765},{"lat":54.110942,"lon":19.599609},{"lat":53.225768,"lon":23.466796}]}],"polygons":[{"pos":[{"lat":62.103882,"lon":5.097656},{"lat":58.309488,"lon":5.71289},{"lat":58.950008,"lon":10.898437},{"lat":61.689872,"lon":12.832031},{"lat":63.352129,"lon":11.865234},{"lat":64.129783,"lon":13.974609},{"lat":65.58572,"lon":14.326171},{"lat":68.560383,"lon":18.28125},{"lat":68.528234,"lon":20.126953},{"lat":69.224996,"lon":20.566406},{"lat":69.224996,"lon":21.708984},{"lat":68.624543,"lon":23.466796},{"lat":69.005675,"lon":25.40039},{"lat":69.960439,"lon":26.630859},{"lat":70.140364,"lon":28.564453},{"lat":69.256149,"lon":28.828125},{"lat":69.839621,"lon":31.201171},{"lat":70.757965,"lon":29.882812},{"lat":71.102542,"lon":25.576171},{"lat":70.728979,"lon":22.675781},{"lat":69.990534,"lon":18.105468},{"lat":67.842416,"lon":13.095703},{"lat":68.334375,"lon":15.908203},{"lat":63.704722,"lon":9.492187},{"lat":63.782486,"lon":8.613281}]}],"rectangles":[{"pos":[{"lat":51.835777,"lon":33.83789},{"lat":46.377254,"lon":23.378906}]}],"circles":[{"radius":[326844.605],"pos":[{"lat":57.421294,"lon":23.90625}]}],"bounds":{"ne":{"lat":71.102542,"lon":33.83789},"sw":{"lat":46.377254,"lon":1.757812}}}'
		);
		$this->assertEquals( $this->object->getErrorMessages(),	[] );
	}

	public function testParseAllElementsWithProperties() {
		$this->assertEquals(
			var_export( $this->object->getMapData( [
				'52.429222,13.359375~Capital of Germany~Crazy people here!; 53.383328,3.339843; 52.5897,2.548828;
52.855864,3.515625; 53.330872,1.757812',
				'circle=57.421294,23.90625:326844.605~I\'m a circle~of doom!',
				'PoLyGoNs=62.103882,5.097656:58.309488,5.71289:58.950008,10.898437
:61.689872,12.832031:63.352129,11.865234:64.129783,13.974609:65.58572,14.326171:
68.560383,18.28125:68.528234,20.126953:69.224996,20.566406:69.224996,21.708984:
68.624543,23.466796:69.005675,25.40039:69.960439,26.630859:70.140364,28.564453:
69.256149,28.828125:69.839621,31.201171:70.757965,29.882812:71.102542,25.576171:
70.728979,22.675781:69.990534,18.105468:67.842416,13.095703:68.334375,15.908203:
63.704722,9.492187:63.782486,8.613281~Meanwhile in Norway~ ~#0B4173~ ~ ~#3373CC',
				'LINES=53.225768,23.466796:53.956085,29.179687:56.46249,31.113281
:59.310767,30.673828:60.84491,27.333984:60.930432,21.269531:59.175928,16.611328:
56.218923,16.259765:54.110942,19.599609:53.225768,23.466796~You\'re serrounded!~ ~#B0920C',
				'rectangles=51.835777,33.83789:46.377254,23.378906~I\'m a square',
				'height=500px',
				'service=leaflet' ] ), true ),
			var_export( [
				'markers' => [
					[
						'pos' => [ [ 'lat' => 52.429222, 'lon' => 13.359375 ] ],
						'title' => "<p>Capital of Germany\n</p>",
						'text' => "<p>Crazy people here!\n</p>",
					],
					[ 'pos' => [ [ 'lat' => 53.383328, 'lon' => 3.339843 ] ] ],
					[ 'pos' => [ [ 'lat' => 52.5897, 'lon' => 2.548828 ] ] ],
					[ 'pos' => [ [ 'lat' => 52.855864, 'lon' => 3.515625 ] ] ],
					[ 'pos' => [ [ 'lat' => 53.330872, 'lon' => 1.757812 ] ] ],
				],
				'lines' => [ [
					'pos' => [
						[ 'lat' => 53.225768, 'lon' => 23.466796 ],
						[ 'lat' => 53.956085, 'lon' => 29.179687 ],
						[ 'lat' => 56.46249, 'lon' => 31.113281 ],
						[ 'lat' => 59.310767, 'lon' => 30.673828 ],
						[ 'lat' => 60.84491, 'lon' => 27.333984 ],
						[ 'lat' => 60.930432, 'lon' => 21.269531 ],
						[ 'lat' => 59.175928, 'lon' => 16.611328 ],
						[ 'lat' => 56.218923, 'lon' => 16.259765 ],
						[ 'lat' => 54.110942, 'lon' => 19.599609 ],
						[ 'lat' => 53.225768, 'lon' => 23.466796 ],
					],
					'title' => "<p>You're serrounded!\n</p>",
					'color' => '#B0920C',
				] ],
				'polygons' => [ [
					'pos' => [
						[ 'lat' => 62.103882, 'lon' => 5.097656 ],
						[ 'lat' => 58.309488, 'lon' => 5.71289 ],
						[ 'lat' => 58.950008, 'lon' => 10.898437 ],
						[ 'lat' => 61.689872, 'lon' => 12.832031 ],
						[ 'lat' => 63.352129, 'lon' => 11.865234 ],
						[ 'lat' => 64.129783, 'lon' => 13.974609 ],
						[ 'lat' => 65.58572, 'lon' => 14.326171 ],
						[ 'lat' => 68.560383, 'lon' => 18.28125 ],
						[ 'lat' => 68.528234, 'lon' => 20.126953 ],
						[ 'lat' => 69.224996, 'lon' => 20.566406 ],
						[ 'lat' => 69.224996, 'lon' => 21.708984 ],
						[ 'lat' => 68.624543, 'lon' => 23.466796 ],
						[ 'lat' => 69.005675, 'lon' => 25.40039 ],
						[ 'lat' => 69.960439, 'lon' => 26.630859 ],
						[ 'lat' => 70.140364, 'lon' => 28.564453 ],
						[ 'lat' => 69.256149, 'lon' => 28.828125 ],
						[ 'lat' => 69.839621, 'lon' => 31.201171 ],
						[ 'lat' => 70.757965, 'lon' => 29.882812 ],
						[ 'lat' => 71.102542, 'lon' => 25.576171 ],
						[ 'lat' => 70.728979, 'lon' => 22.675781 ],
						[ 'lat' => 69.990534, 'lon' => 18.105468 ],
						[ 'lat' => 67.842416, 'lon' => 13.095703 ],
						[ 'lat' => 68.334375, 'lon' => 15.908203 ],
						[ 'lat' => 63.704722, 'lon' => 9.492187 ],
						[ 'lat' => 63.782486, 'lon' => 8.613281 ],
					],
					'title' => "<p>Meanwhile in Norway\n</p>",
					'color' => '#0B4173',
					'fill' => true,
					'fillcolor' => '#3373CC',
				] ],
				'rectangles' => [ [
					'pos' => [
						[ 'lat' => 51.835777, 'lon' => 33.83789 ],
						[ 'lat' => 46.377254, 'lon' => 23.378906 ],
					],
					'title' => "<p>I'm a square\n</p>",
				] ],
				'circles' => [ [
					'radius' => [ 326844.605 ],
					'pos' => [ [ 'lat' => 57.421294, 'lon' => 23.90625 ] ],
					'title' => "<p>I'm a circle\n</p>",
					'text' => "<p>of doom!\n</p>",
				] ],
				'bounds' => [
					'ne' => [ 'lat' => 71.102542, 'lon' => 33.83789 ],
					'sw' => [ 'lat' => 46.377254, 'lon' => 1.757812 ],
				]
			], true )
		);
		$this->assertEquals( $this->object->getErrorMessages(),	[] );
	}

	public function testMessageUnknownParameter() {
		$badparam = 'thisisabadparameter';
		$badvalue = 'thisisabadvalue';

		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ '55.755786, 37.617633', "$badparam=$badvalue", 'service=leaflet' ] ) ),
			'{"markers":[{"pos":[{"lat":55.755786,"lon":37.617633}]}],"zoom":14,"center":{"lat":55.755786,"lon":37.617633}}'
		);

		$this->assertEquals(
			$this->object->getErrorMessages(),
			[ \wfMessage( 'multimaps-unknown-parameter', $badparam )->escaped() ]
		);
	}

	public function testMessageElementMoreParameters() {
		$marker = new Marker();

		$this->assertEquals(
			$this->object->getMapData( [ '52.429222,13.359375~Capital of Germany~Crazy people here!~ ~ ~Berlin;', 'service=leaflet' ] ),
			[
				'markers' => [
					[
						'pos' => [ [ 'lat' => 52.429222, 'lon' => 13.359375 ] ],
						'title' => "<p>Capital of Germany\n</p>",
						'text' => "<p>Crazy people here!\n</p>",
					],
				],
				'zoom' => 14,
				'center' => [ 'lat' => 52.429222, 'lon' => 13.359375 ],
			]
		);

		$this->assertEquals(
			$this->object->getErrorMessages(),
			[
				\wfMessage( 'multimaps-element-more-parameters', $marker->getElementName() )->escaped(),
				\wfMessage( 'multimaps-element-parameters-not-processed', '"' . implode( '", "', [ ' ', 'Berlin' ] ) . '"' )->escaped(),
			]
		);
	}

	public function testMessageMarkerIncorrectIcon() {
		$badicon = 'this is a bad icon';

		$this->assertEquals(
			\FormatJson::encode( $this->object->getMapData( [ "55.755786, 37.617633~icon=$badicon", 'service=leaflet' ] ) ),
			'{"markers":[{"pos":[{"lat":55.755786,"lon":37.617633}]}],"zoom":14,"center":{"lat":55.755786,"lon":37.617633}}'
		);

		$this->assertEquals(
			$this->object->getErrorMessages(),
			[ \wfMessage( 'multimaps-marker-incorrect-icon', $badicon )->escaped() ]
		);
	}

	public function testParseGeocoderMarker() {
		global $egMultiMaps_AllowGeocoderTests;
		if ( !$egMultiMaps_AllowGeocoderTests ) {
			$this->markTestSkipped( 'Requires $egMultiMaps_AllowGeocoderTests' );
		}
		$this->assertRegExp(
			'{"markers":\[{"pos":\[{"lat":[0-9\.]+,"lon":[0-9\.]+}\]}\],"bounds":{"ne":{"lat":[0-9\.]+,"lon":[0-9\.]+},"sw":{"lat":[0-9\.]+,"lon":[0-9\.]+}}}',
			\FormatJson::encode( $this->object->getMapData( [ 'Moscow', 'service=leaflet' ] ) )
		);
	}

	public function testParseGeocoderRectangle() {
		global $egMultiMaps_AllowGeocoderTests;
		if ( !$egMultiMaps_AllowGeocoderTests ) {
			$this->markTestSkipped( 'Requires $egMultiMaps_AllowGeocoderTests' );
		}
		$this->assertRegExp(
			'{"rectangles":\[{"pos":\[{"lat":[0-9\.]+,"lon":[0-9\.]+},{"lat":[0-9\.]+,"lon":[0-9\.]+}\]}\],"bounds":{"ne":{"lat":[0-9\.]+,"lon":[0-9\.]+},"sw":{"lat":[0-9\.]+,"lon":[0-9\.]+}}}',
			\FormatJson::encode( $this->object->getMapData( [ 'rectangle=Moscow', 'service=leaflet' ] ) )
		);
	}

	public function testParseGeocoderRectangles() {
		global $egMultiMaps_AllowGeocoderTests;
		if ( !$egMultiMaps_AllowGeocoderTests ) {
			$this->markTestSkipped( 'Requires $egMultiMaps_AllowGeocoderTests' );
		}
		$this->assertRegExp(
			'{"rectangles":\[{"pos":\[{"lat":[0-9\.]+,"lon":[0-9\.]+},{"lat":[0-9\.]+,"lon":[0-9\.]+}\]},{"pos":\[{"lat":[0-9\.]+,"lon":[0-9\.]+},{"lat":[-0-9\.]+,"lon":[-0-9\.]+}]}],"bounds":{"ne":{"lat":[0-9\.]+,"lon":[0-9\.]+},"sw":{"lat":[0-9\.]+,"lon":[-0-9\.]+}}}',
			\FormatJson::encode( $this->object->getMapData( [ 'rectangle=Moscow;London', 'service=leaflet' ] ) )
		);
	}

	public function testParseGeocoderCircle() {
		global $egMultiMaps_AllowGeocoderTests;
		if ( !$egMultiMaps_AllowGeocoderTests ) {
			$this->markTestSkipped( 'Requires $egMultiMaps_AllowGeocoderTests' );
		}
		$this->assertRegExp(
			'{"circles":\[{"radius":\[[0-9\.]+\],"pos":\[{"lat":[0-9\.]+,"lon":[0-9\.]+}\]}\],"bounds":{"ne":{"lat":[0-9\.]+,"lon":[0-9\.]+},"sw":{"lat":[0-9\.]+,"lon":[0-9\.]+}}}',
			\FormatJson::encode( $this->object->getMapData( [ 'circle=Moscow', 'service=leaflet' ] ) )
		);
	}

	public function testParseGeocoderObjectPolygon() {
		global $egMultiMaps_AllowGeocoderTests;
		if ( !$egMultiMaps_AllowGeocoderTests ) {
			$this->markTestSkipped( 'Requires $egMultiMaps_AllowGeocoderTests' );
		}
		$this->assertRegExp(
			'{"polygons":\[\{"pos":\[(\{"lat":[0-9\.]+,"lon":[0-9\.]+\},?)+\]\}\],"bounds":{"ne":{"lat":[0-9\.]+,"lon":[0-9\.]+},"sw":{"lat":[0-9\.]+,"lon":[0-9\.]+}}}',
			\FormatJson::encode( $this->object->getMapData( [ 'polygon=Astana', 'service=leaflet' ] ) )
		);
	}

	public function testParseWikitext() {
		$title = \Title::makeTitle( NS_SPECIAL, 'Badtitle/Dummy' );
		$options = \ParserOptions::newFromAnon();
		$parser = MediaWikiServices::getInstance()->getParser();
		$this->assertEquals(
			var_export( $this->object->getMapData( [ '52.429222,13.359375~Capital of [[Germany]]~Crazy [[people|germans]] here!', 'service=leaflet' ] ), true ),
			var_export( [
				'markers' => [
					[
						'pos' => [ [ 'lat' => 52.429222, 'lon' => 13.359375 ] ],
						'title' => $parser->parse( 'Capital of [[Germany]]', $title, $options )->getText( [ 'unwrap' => true ] ),
						'text' => $parser->parse( 'Crazy [[people|germans]] here!', $title, $options )->getText( [ 'unwrap' => true ] ),
					],
				],
				'zoom' => 14,
				'center' => [ 'lat' => 52.429222, 'lon' => 13.359375 ],
			], true )
		);
	}
}
