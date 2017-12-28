<chart>
	<license><?php echo $LICENSE_MAANI;?></license><!-- sps.net.my -->

	
	<axis_category shadow='shadow2' size='12' color='000000' alpha='25' orientation='circular' />
	<!-- 
	<axis_ticks value_ticks='1' category_ticks='1' major_color='444444' major_thickness='2' minor_count='0' />
	 -->
	<axis_value max='5' size='11' alpha='90' color='ffffff' show_min='1' background_color='446688' />
	
	<chart_border bottom_thickness='0' left_thickness='0' />
	<chart_data>
		<row>
			<null/>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
		</row>
		<row>
			<string>car 1</string>
			<number>1</number>
			<number>2</number>
			<number>3</number>
			<number>4</number>
			<number>5</number>
		</row>
		<row>
			<string>car 2</string>
			<number>1</number>
			<number>2</number>
			<number>3</number>
			<number tooltip='4'>4</number>
			<number>5</number>
		</row>
	</chart_data>
	<chart_grid_h alpha='20' color='000000' thickness='1' type='dashed' />
	<chart_grid_v alpha='5' color='000000' thickness='20' type='solid' />
	<chart_pref point_shape='circle' point_size='8' fill_shape='true' grid='circular' />
	<chart_rect bevel='bevel1' x='90' y='30'  positive_color='008888' positive_alpha='25' />
	<chart_transition type='zoom' delay='1' duration='1' order='series' />
	<chart_type>polar</chart_type>
	

	
	
	<legend shadow='shadow2' x='40' y='100' width='20' height='40' margin='3' fill_alpha='0' layout='vertical' bullet='circle' size='12' color='4e627c' alpha='75' />
	
	
	
</chart>