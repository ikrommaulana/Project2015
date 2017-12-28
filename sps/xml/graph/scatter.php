<chart>

	<axis_category shadow='low' size='12' color='000000' alpha='85' orientation='horizontal' />
	<axis_ticks value_ticks='false' category_ticks='true' major_thickness='1' minor_thickness='0' minor_count='1' major_color='000000' minor_color='222222' position='inside' />
	<axis_value min='0' max='12' size='10' color='ffffff' alpha='60' prefix='' suffix='' decimals='0' separator='' show_min='false' />

	<chart_border color='222222' top_thickness='0' bottom_thickness='3' left_thickness='0' right_thickness='0' />
	<chart_data>
		<row>
			<null/>
			<string>x</string>
			<string>y</string>
            <string>y</string>
		</row>
		<row>
			<string>veh 1</string>
			<number shadow='low'>90</number>
			<number>500</number>
            <number>600</number>
		</row>
		<row>
			<string>veh 2</string>
			<number shadow='low'>105</number>
			<number>400</number>
		</row>
		<row>
			<string>veh 3</string>
			<number shadow='low' label='115, 600\r(best)'>115</number>
			<number>600</number>
		</row>
		<row>
			<string>veh 4</string>
			<number shadow='low'>85</number>
			<number>650</number>
		</row>
	</chart_data>
	<chart_grid_h alpha='10' color='000000' thickness='1' />
	<chart_grid_v alpha='10' color='000000' thickness='1' />
	<chart_guide radius='6' line_color='ff4400' line_alpha='90' line_thickness='2' />	
	<chart_pref point_size='7' trend_alpha='20' trend_thickness='2' />
	<chart_rect x='10' y='10' width='300' height='250' positive_color='000000' positive_alpha='25' />
	<chart_type>scatter</chart_type>

	<draw>
		<rect shadow='shadow1' layer='background' x='0' y='0' width='480' height='600' fill_color='4c5e6f' fill_alpha='100' line_alpha='0' line_thickness='0' />

		<text shadow='high' color='ffffff' alpha='30' size='35' x='115' y='238' width='400' height='150' h_align='left' v_align='top'>Distance</text>
		<text shadow='high' color='ffffff' alpha='30' rotation='-90' size='35' x='45' y='228' width='200' height='60' v_align='bottom'>Score</text>
		<text shadow='low' color='ffffff' alpha='3' rotation='-10' size='100' x='300' y='180'>@</text>
		<text shadow='low' color='ffffff' alpha='3' rotation='10' size='100' x='35' y='100'>@</text>
		<text shadow='low' color='ffffff' alpha='3' rotation='45' size='100' x='265' y='-20'>@</text>
		<text shadow='low' color='ffffff' alpha='3' rotation='-45' size='100' x='50' y='-10'>@</text>
		<text shadow='low' color='ffffff' alpha='3' rotation='0' size='100' x='390' y='30'>@</text>
	</draw>


	<legend  shadow='low' x='115' y='30' width='10' height='35' margin='3' fill_color='ffffff' fill_alpha='0' line_color='000000' line_alpha='0' line_thickness='0' layout='vertical' size='12' color='ffffff' alpha='75' />
	<tooltip color='ff4400' alpha='80' size='12' background_alpha='0' shadow='low' />

	<series_color>
		<color>88ff00</color>
		<color>ff8800</color>
	</series_color>
	
</chart>