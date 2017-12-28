<?php
	include_once('../../etc/db_js.php');
	//include_once('../etc/session.php');
	$dat=$_REQUEST['dat'];
	list($xbuf,$xval,$gynote,$gtnote,$gxnote,$gcnote,$grnote,$gdec) = split('[|]', $dat);
	
?>
<chart>

	<axis_category alpha='0' />
	<axis_ticks value_ticks='true' category_ticks='false' major_thickness='2' minor_thickness='1' minor_count='1' minor_color='000000' position='outside' />
	<axis_value shadow='none' min='0' max='100' size='10' color='000000' alpha='50' steps='6' />
	<chart_border top_thickness='2' bottom_thickness='2' left_thickness='2' right_thickness='2' />
	<chart_data>
		<row>
			<null/>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
			<string></string>
	
		</row>
		<row>
			<string>Region A</string>
			<number shadow='low'>10</number>
			<number>12</number>
			<number>11</number>
			<number>15</number>
			<number>20</number>
			<number>22</number>
			<number>21</number>
			<number>25</number>
			<number>31</number>
			<number>32</number>
			<number>28</number>
			<number>29</number>
			<number>100</number>
			<number>41</number>
			<number>45</number>
			<number>50</number>
			<number>65</number>
			<number>45</number>
			<number>50</number>
			<number>51</number>
			<number>65</number>
			<number>60</number>
			<number>62</number>
			<number>65</number>
			<number>45</number>
			<number>55</number>
			<number>59</number>
			<number>52</number>
			<number>53</number>
			<number>100</number>
			<number>45</number>
			<number>32</number>
			<number>35</number>
			<number>100</number>
		</row>
		<row>
			<string>Region B</string>
			<number shadow='high'>30</number>
			<number>32</number>
			<number>35</number>
			<number>100</number>
			<number>42</number>
			<number>35</number>
			<number>36</number>
			<number>31</number>
			<number>35</number>
			<number>36</number>
			<number>100</number>
			<number>42</number>
			<number>100</number>
			<number>38</number>
			<number>100</number>
			<number>100</number>
			<number>38</number>
			<number>36</number>
			<number>30</number>
			<number>29</number>
			<number>28</number>
			<number>25</number>
			<number>28</number>
			<number>29</number>
			<number>30</number>
			<number>100</number>
			<number>32</number>
			<number>33</number>
			<number>34</number>
			<number>30</number>
			<number>35</number>
			<number>12</number>
			<number>11</number>
			<number>15</number>
			<number>100</number>
		</row>
	</chart_data>
    
	<chart_grid_h alpha='10' thickness='1' type='solid' />
	<chart_grid_v alpha='10' thickness='1' type='solid' />
	<chart_guide horizontal='true' thickness='1' color='ffffff' alpha='50' type='dashed' radius='5' line_color='FFFF00' line_alpha='75' line_thickness='2' text_color='FFFF00' text_h_alpha='90' />	
	<chart_note type='bullet' size='11' color='AAAAFF' alpha='90' x='0' y='-125' offset_y='5' background_color_1='8888FF' background_alpha='65' shadow='low' />
	<chart_pref line_thickness='2' point_shape='none' fill_shape='false' />
	<chart_rect x='30' y='20' width='350' height='210' positive_color='000000' positive_alpha='30' bevel='bg' />
	<chart_type>Line</chart_type>

	<draw>
		<rect shadow='bg' layer='background' x='0' y='0' width='480' height='300' fill_color='4c5577' fill_alpha='100' line_alpha='0' line_thickness='0' />
   	</draw>

	<filter>
		<shadow id='low' distance='2' angle='45' alpha='50' blurX='5' blurY='5' />
		<shadow id='high' distance='5' angle='45' alpha='25' blurX='10' blurY='10' />
		<bevel id='bg' angle='45' blurX='15' blurY='15' distance='5' highlightAlpha='25' highlightColor='ffffff' shadowAlpha='50' type='outer' />
	</filter>
	
	<legend layout='hide' />
<!--
	<series_color>
		<color>77bb11</color>
		<color>cc5511</color>
	</series_color>
-->
</chart>